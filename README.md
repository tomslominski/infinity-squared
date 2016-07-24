Infinity Squared
================

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/tomslominski)

A beautiful public page theme for YOURLS, carefully crafted by [Tom Slominski](http://tomslominski.net/). It can be used to give the public access to your short domain, not just registered users.

![Front page of Infinity Squared 2.0](http://i.imgur.com/Wc1cVRF.png)
###### Front page of Infinity Squared 2.0

#### **[See more screenshots](http://imgur.com/a/f4g0x)**

Features
--------
* A clear design, with a responsive design which looks great across all screen sizes and resolutions.
* Antispam protection: logged in users can shorten freely, otherwise reCAPTCHA keys can be provided or basic antispam protection will be provided.
* Social sharing buttons for Facebook, Twitter, Google+, LinkedIn, Tumblr and App.net.
* User configurable settings which are not overwritten on upgrade.
* Translation ready (Russian and Polish are included).
* Bookmarklets, so that links can be shortened quickly from the bookmarks bar.

Installation
------------
1. Download the latest [release](https://github.com/tomslominski/infinity-squared/releases) from GitHub and enter the folder which houses `index.php`.
2. Upload all of the files into the directory where you've installed YOURLS. It doesn't have any additional requirements. If you can run YOURLS, you can run ∞²! Some of the files might collide with the files YOURLS also provides, like `README.md`. You can choose to replace them or not, it doesn't matter.
3. Rename `public/config-sample.php` to `public/config.php` and make ∞² suit you.

Upgrade
-------
1. Download the latest [release](https://github.com/tomslominski/infinity-squared/releases) from GitHub and enter the folder which houses `index.php`.
2. Remember to do a backup before you make any changes on your server.
3. Replace all of the files from the downloaded release with the release on your server. **Remember that if you've made any changes to the core theme files, they will be overwritten, so be careful!** A `config.php` is not provided with the release, so you don't need to worry about loosing your settings.
4. Copy over any new settings from `config-sample.php` into your own `config.php`.

Customisation
-------------
You can customise Infinity Squared by editing the `public/config.php` file. The file explains what each of the settings does and how to modify them.

You can also create a `public/custom.css` style and change the appropriate setting in `config.php` to enable you to add your own CSS which will not be overwritten on upgrade.

Translating
-----------
By default, Infinity Squared comes with Polish and Russian translations, as well as it's default English language. To enable any of those languages, you need to edit YOURLS' `config.php`, as described [here](https://github.com/YOURLS/YOURLS/wiki/YOURLS-in-your-language#install-yourls-in-your-language).

If you want to translate Infinity Squared into your own language, [this blog post](http://blog.yourls.org/2013/02/workshop-how-to-create-your-own-translation-file-for-yourls/) from YOURLS describes how to do it. You can find the latest .pot file in `public/languages/isq_translation-source.pot`. Please follow the contributing guidelines below to add your translation to Infinity Squared.

Contributing
------------
If you have any issues with the way Infinity Squared works, you want to suggest a feature or you believe you have found a bug, please submit an issue using GitHub's [issue system](https://github.com/tomslominski/infinity-squared/issues). However, please remember that the developers work in this project in their spare time.

If you'd like to contribute some code to Infinity Squared, please open an issue first to discuss whether your patch will be accepted. If it has been agreed that your patch will be accepted, please fork the repository and submit a pull request when ready.

Licensing
---------
Just like YOURLS, Infinity Squared is licensed under the MIT license. Basically, you can do whatever you want with it as long as you give attribution wherever you use it. There is no guarantee that this software will work.

You can find the full license in the root directory of Infinity Squared, under LICENSE.md.

Clipboard.js is also licensed under the MIT license.

Donations
---------
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/tomslominski)

Even though Infinity Squared is free, it takes time to build, so I'm more than happy to accept donations at the above link. Thanks!
