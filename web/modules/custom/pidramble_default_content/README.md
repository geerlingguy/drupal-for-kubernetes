# Pi Dramble Default Content Module

This module requires the [Default Content for D8](https://www.drupal.org/project/default_content) module.

To update exported content, run:

    drush dcer node 1 --folder=modules/custom/pidramble_default_content/content/

You can substitute any entity type for `node` (e.g. `file`, `user`, `block_content`), and when any entity is exported, referenced entities are also exported at the same time.

## Default Files

This module includes a few files which are copied into the Drupal installation inside `pidramble_default_content_install()`. These files are referenced by the file entities in `content/file`.

If updating the file entity itself (e.g. with a newer version of the default files), you should also update the file stored inside `default_files`.

If adding a new file, make sure you add it to the array of files to be copied inside `pidramble_default_content_install()`.
