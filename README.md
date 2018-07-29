cooluri
=======

**ATTENTION**: This is **not** the official CoolUri TYPO3 extension distribution (which can be found under [bednee/cooluri](https://github.com/bednee/cooluri)) but a quick'n'dirty / modified version that *seems* to work for a simple, monolingual TYPO3 9.3 site. It's pretty untested and there's no guarantee that it will work for you. It will most likely disappear again when [TYPO3 9.4 will be released](https://typo3.org/cms/roadmap/), then hopefully finally supporting native URL routing (🤘).

To install into a composer based TYPO3 instance, add this respository to your `composer.json`:

```
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:tollwerk/cooluri.git",
      "reference": "master"
    }
  ]
}
```

and require the `master` branch:

```
composer require bednee/cooluri:@dev
```

Activating the extension via the Extension Manager resulted in a blank page for us, but this might also be a different problem with our setup (seeing error messages about the Doctrine Lexer in the logs). The extension could be installed successfully. However, it left us with the necessary database tables / columns not being created. You might have to do this by hand (use `ext_tables.sql` for inspiration).

And finally: Enabling CoolUri seems to bring a significant frontend performance penalty. It's really, really time for a native solution. *sigh*