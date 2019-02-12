# Add a custom theme

While Drupal's default theme Bartik _is_ responsive, and isn't ugly as sin... it's probably not a theme that makes your heart go aflutter, or makes you feel incredibly proud of your very own Drupal site. And since we installed our site using the `minimal` installation profile, the site is using the Stark theme, which is, well, _very_ stark!

So at some point you might want to create your own theme, and start giving your site your own design sensibilities.

This is something I usually do very early in the project, and I usually start pretty barebones, just styling the general layout, the text (font, heading styles, things like that), and a few very basic things.

So first, create a super simple new theme, following the [Drupal 8 Theming Guide](https://www.drupal.org/docs/8/theming). You can take a look at the [`pidramble` theme](../web/themes/custom/pidramble) in this repository for a reference of a very basic functional Drupal 8 theme based on the 'Classy' theme that ships with Drupal core.

Once you create the theme (all you really need at this point is a `themename.info.yml` file—everything else just makes the theme better!), you can enable it and set it as the default:

  1. Go to the Appearance page (`/admin/appearance`).
  1. Find your theme under Uninstalled themes and click "Install and set as default".
  1. Disable the Stark theme since you don't want to use it anymore.
  1. (This is personal preference, but I usually do it) Also enable the 'Seven' theme so you can use it as the 'administration' theme, then choose it as the Administration theme down at the bottom, and click 'Save configuration'.

Now if you visit your site's home page, you should see your very own custom theme! This is wonderful!

However, you need to remember to _export the site's configuration_ now, so the theme preferences are all stored safely and securely in your code repository, ready to be reinstalled or deployed to another environment:

  1. Run the command:

     ```
     docker-compose exec drupal bash -c 'vendor/bin/drush config:export -y'
     ```

  1. Commit the changed configuration to Git:

     ```
     git add -A
     git commit -m "Add the site's custom theme and enable it in the config."
     ```

Are you sensing a theme to all these changes?

The basic pattern for anything you do to your site—whether it be theming, custom code, adding modules, changing configurations, updating Drupal core or contributed modules is:

  1. Install Drupal fresh (so you have a fresh slate to work on).
  1. Do stuff (change a setting, modify code, etc.).
  1. Export the site configuration (so all your required settings changes are stored in the code).
  1. Commit all the changes to Git (so you can reproduce your changes and/or deploy them to another environment).
