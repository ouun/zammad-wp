# Zammad for WordPress

This plugin helps you embed Zammad Chats & Forms into your WordPress site and gives you Access to the Zammad API if required.
It is based on WordPress best practise, keeping your workplace clean by using functions, hooks and filters instead of cluttered dashboard pages.

## Install
I recommend you install the package as a Composer dependency:

`composer require ouun/zammad-wp`

But you can also download the package and install it as a regular WordPress Plugin.

## Prepare

If you only need the Chat and Form functionality, only `ZAMMAD_URL` is required in `wp-config.php`:

```
define('ZAMMAD_URL', 'https://my-zammad.com')
```

If you want to interact with the Zammad API via `ZammadWp` Class, you will additionally need:

```
define('ZAMMAD_USERNAME', 'username')
define('ZAMMAD_PASSWORD', 'password')
```

or if you prefere to authenticate via HTTP- or AUTH-Token:

```
define('ZAMMAD_HTTP_TOKEN', 'token')
define('ZAMMAD_AUTH_TOKEN', 'token')
```

Further options are:

```
define('ZAMMAD_DEBUG', false) # Debug mode
define('ZAMMAD_ON_BEHALF_USER') # User ID used to interact with the API
define('ZAMMAD_TIMEOUT', 10) # Seconds until connection timeout
```

## Zammad Chat

### Embed a chat
Use the `zammad_register_chat` function either within `init`or `admin_init` hook to add a chat.

```
add_action('init', function () {
        if (function_exists('zammad_register_chat')) {
            zammad_register_chat($chatId, $args);
        }
    });
```

This is very flexible as you can register different chat topics e.g. based on a users state (logged in/out, member/visitor,...) or any other WordPress conditions.
Only set the `$chatId` to the topic you have previously set up in Zammad. You can override the chat options individually by using the second `$args` parameter (see next section for available options).
So a Dashboard integration could look like this:

```
add_action('admin_init', function () {
    if (function_exists('zammad_register_chat')) {
        // Logged in user at least with editor role, load chat with topic '2'
        if (is_user_logged_in() && current_user_can('edit_posts')) {
            zammad_register_chat( 2, [
                'buttonClass' => 'button action',
                'target' => '$("#wpbody")'
            ] );
        }
    }
});
```

### Change option defaults
You can change the default options of Zammad Chat via `zammad_wp:chat:defaults` filter.

```
add_filter('zammad_wp:chat:defaults', function ($defaults) {
    return wp_parse_args(
        [
            'debug' => true,
            'title' => __('Talk to us!', 'text-domain')
        ],
        $defaults
    );
});
```

The available options are:

| Options                    | Default                            | Type            | Description                                                                                                                                                                                                              |
| -------------------------- | ---------------------------------- | --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| chatId                     | `1`                                | `Number`        | Default identifier of the chat-topic.                                                                                                                                                                                    |
| background                 | `#0073aa`                          | `HEX`           | Default background color.                                                                                                                                                                                                |
| show                       | `true`                             | `Boolean`       | Show the chat when ready.                                                                                                                                                                                                |
| target                     | `$('body')`                        | `jQuery Object` | Where to append the chat to.                                                                                                                                                                                             |
| host                       | `(Empty)`                          | `String`        | If left empty, the host gets auto-detected. The auto-detection reads out the host from the <script> tag. If you don't include it via a <script> tag you need to specify the host.                                        |
| debug                      | `false`                            | `Boolean`       | Enables console logging.                                                                                                                                                                                                 |
| title                      | `'<strong>Chat</strong> with us!'` | `String`        | Welcome Title shown on the closed chat. Can contain HTML.                                                                                                                                                                |
| fontSize                   | `undefined`                        | `String`        | CSS font-size with a unit like 12px, 1.5em. If left to undefined it inherits the font-size of the website.                                                                                                               |
| flat                       | `false`                            | `Boolean`       | Removes the shadows for a flat look.                                                                                                                                                                                     |
| buttonClass                | `'open-zammad-chat'`               | `String`        | Add this class to a button on your page that should open the chat.                                                                                                                                                       |
| inactiveClass              | `'is-inactive'`                    | `String`        | This class gets added to the button on initialization and gets removed once the chat connection got established.                                                                                                         |
| cssAutoload                | `true`                             | `Boolean`       | Automatically loads the chat.css file. If you want to use your own css, just set it to false.                                                                                                                            |
| cssUrl                     | `undefined`                        | `String`        | Location of an external chat.css file.                                                                                                                                                                                   |
| formFallback               | `false`                            | `Boolean`       | Fallback to form, see next section for description                                                                                                                                                                       |
| formFallbackMessage        | See includes/function/chat.php     | `String`        | Message displayed above the Fallback Form, if no agent is available                                                                                                                                                      |
| loaderWaitingMessage       | See includes/function/chat.php     | `String`        | Message in chat modal, when waiting for agent to answer                                                                                                                                                                |
| waitingListWaitingMessage  | See includes/function/chat.php     | `String`        | Message in chat modal, if user already waits a bit longer                                                                                                                                                                |
| waitingListTimeoutMessage  | See includes/function/chat.php     | `String`        | Message displayed above the Fallback Form, if user waited too long                                                                                                                                                       |


### Embed a chat with form fallback
The Zammad team states a bad user experience if "a chat window will appear on the website, but no one is online. Just a “Leave a message” form shows up."
This is why such a functionality is not embedded in Zammad. if no agent is available to talk to, the chat is hidden.
However, as a client stated: "We need to give the impression that we are there, ready to support. You know what I mean."
So against the recommendation of Zammad we introduced a workaround to load the Zammad form within the chat modal, if no agent is available.

```
add_action('init', function () {
    zammad_register_chat( 1, [
        'formFallback' => true
    ] );
});
```

## Zammad Forms
Embedding forms is quite similar to embedding a chat. However there are some differences you need to take care of:

- Zammad currently only supports one single form. Sadly there are no topics, yet.
- An element in the DOM is required. It is either the modal trigger, or the form loads within that. This is described in more detail below.

### Embed a form
Please use the `zammad_register_form()` function to add a form. First parameter defines the target DOM element, second allows overwriting default options.

```
add_action('init', function () {
    if (function_exists('zammad_register_form')) {
        zammad_register_form('#feedback-form');
    }
});
```

As mentioned before you can either open the form in a modal or directly embed it into an element. If you want to embed it, set the `modal` option to `false`;
Below an example how you could embed a DOM element via WordPress action into the footer:

```
add_action('wp_footer', function () {
    // This is an example trigger button (modal: true)
    echo '<button id="feedback-form">Open Feedback Modal</div>';

    // This is an example container div the form is embedded in (modal: false)
    echo '<div id="feedback-form"></div>';
});
```

### Change option defaults
You can change the default options of Zammad Form via `zammad_wp:form:defaults` filter.

```
add_filter('zammad_wp:chat:defaults', function ($defaults) {
    return wp_parse_args(
        [
            'debug' => true,
            'title' => __('Talk to us!', 'text-domain')
        ],
        $defaults
    );
});
```

| Options           | Default                                                                       | Type            | Description                                                                                                                                                                                                              |
| ----------------- | ----------------------------------------------------------------------------- | --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| formElement       | `'#feedback-form'`                                                            | `String`        | DOM Element the form is attached to                                                                                                                                                                                      |
| debug             | `false`                                                                       | `Boolean`       | Enable debugging for implementation.                                                                                                                                                                                     |
| modal             | `true`                                                                        | `Boolean`       | Start modal dialog for form.                                                                                                                                                                                             |
| showTitle         | `true`                                                                        | `Boolean`       | Show title in form.                                                                                                                                                                                                      |
| messageTitle      | `'Feedback Form'`                                                             | `String`        | Form Title                                                                                                                                                                                                               |
| messageSubmit     | `'Submit'`                                                                    | `String`        | Submit Button label                                                                                                                                                                                                      |
| messageThankYou   | `'Thank you for your inquiry (#%s)! We\'ll contact you as soon as possible.'` | `Boolean`       | Thank you message after form submit.                                                                                                                                                                                     |
| attachmentSupport | `false`                                                                       | `Boolean`       | Add attachment option to upload.                                                                                                                                                                                         |


## Build the package

### Webpack config

Webpack config files can be found in `config` folder:

- `webpack.config.dev.js`
- `webpack.config.common.js`
- `webpack.config.prod.js`
- `webpack.settings.js`

In most cases `webpack.settings.js` is the main file which would change from project to project. For example adding or removing entry points for JS and CSS.

### NPM Commands

- `npm run test` (runs phpunit)
- `npm run start` (install dependencies)
- `npm run watch` (watch)
- `npm run build` (build all files)
- `npm run build-release` (build all files for release)
- `npm run dev` (build all files for development)
- `npm run lint-release` (install dependencies and run linting)
- `npm run lint-css` (lint CSS)
- `npm run lint-js` (lint JS)
- `npm run lint-php` (lint PHP)
- `npm run lint` (run all lints)
- `npm run format-js` (format JS using eslint)
- `npm run format` (alias for `npm run format-js`)
- `npm run test-a11y` (run accessibility tests)

### Composer Commands

`composer lint` (lint PHP files)

`composer lint-fix` (lint PHP files and automatically correct coding standard violations)

## Contributing

We welcome pull requests and spirited, but respectful, debates. Please contribute via [pull requests on GitHub](https://github.com/ouun/zammad-wp/compare).

1. Fork it!
2. Create your feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -am 'Added some great feature!'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Submit a pull request
