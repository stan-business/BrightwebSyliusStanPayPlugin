import chug from 'gulp-chug';
import gulp from 'gulp';
import yargs from 'yargs';

import realSass from 'sass';
import gulpSass from 'gulp-sass';

const sass = gulpSass(realSass);

const { argv } = yargs
  .options({
    rootPath: {
      description: '<path> path to public assets directory',
      type: 'string',
      requiresArg: true,
      required: false,
    },
    nodeModulesPath: {
      description: '<path> path to node_modules directory',
      type: 'string',
      requiresArg: true,
      required: false,
    },
  });

const config = [
  '--rootPath',
  argv.rootPath || '../../../../../../../tests/Application/public/assets',
  '--nodeModulesPath',
  argv.nodeModulesPath || '../../../../../../../tests/Application/node_modules',
];

export const buildAdmin = function buildAdmin() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config, tasks: 'build' }));
};
buildAdmin.description = 'Build admin assets.';

export const watchAdmin = function watchAdmin() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config, tasks: 'watch', sass }));
};
watchAdmin.description = 'Watch admin asset sources and rebuild on changes.';

export const buildShop = function buildShop() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config, tasks: 'build', sass }));
};
buildShop.description = 'Build shop assets.';

export const watchShop = function watchShop() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config, tasks: 'watch', sass }));
};
watchShop.description = 'Watch shop asset sources and rebuild on changes.';

export const build = gulp.parallel(buildAdmin, buildShop);
build.description = 'Build assets.';

gulp.task('admin', buildAdmin);
gulp.task('admin-watch', watchAdmin);
gulp.task('shop', buildShop);
gulp.task('shop-watch', watchShop);

export default build;
