# Zammad for WordPress

This plugin helps you embed Zammad Chats & Forms into your WordPress site and gives you Access to the Zammad API if required.
It is based on WordPress best practise, keeping your workplace clean by using functions, hooks and filters instead of cluttered dashboard pages.

## Documentation
As the documentation grows, please look at the [Zammad WP Wiki](https://github.com/ouun/zammad-wp/wiki/).

## Compatibility
Currently, Zammad WP is compatible with the following Form plugins to replace the Zammad standard form with custom & complex ones as documented in the [Wiki](https://github.com/ouun/zammad-wp/wiki/).

- [HTML Forms by Ibericode](https://github.com/ibericode/html-forms)

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
