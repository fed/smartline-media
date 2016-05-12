# Smartline Media Server API

![Smartline](http://i.imgur.com/UHyyc4e.png)

## Prerequisites

* Node.js (http://nodejs.org/)
* Grunt CLI (http://gruntjs.com/)

Run `npm install` the first time you download the app in order to build the binaries for any node modules.

Grunt CLI can be installed globally for convenience with `npm install -g grunt-cli`, otherwise the binary can be found here: `./node_modules/grunt-cli/bin/grunt`.

## Development Tasks

| Command           | Description                      |
|-------------------|----------------------------------|
| `npm install`     | Fetch dependencies               |
| `grunt`           | Watch for changes on .php files  |
| `grunt build`     | Build all the things!            |

## Usage

To serve images:

```
./?id=<IMAGE HASH>
./?id=<IMAGE HASH>&width=<WIDTH>
./?id=<IMAGE HASH>&width=<WIDTH>&height=<HEIGHT>
```

To upload images:

```
<form method="POST" action="upload.php" enctype="multipart/form-data">
  <input type="file" name="image" />
  <input type="submit" value="Upload" />
</form>
```

## Release Versions

To version and tag a release, move to `master` and run the [appropriate command](https://docs.npmjs.com/cli/version):

```
npm version major  # bump major version, eg. 1.0.2 -> 2.0.0
npm version minor  # bump minor version, eg. 0.1.3 -> 0.2.0
npm version patch  # bump patch version, eg. 0.0.1 -> 0.0.2
```

Make sure to push tags:

```
git push --tags origin master
```

## Semantic Versioning

Given a version number `MAJOR.MINOR.PATCH`, increment the:

1. `MAJOR` version when you make incompatible API changes,
2. `MINOR` version when you add functionality in a backwards-compatible manner, and
3. `PATCH` version when you make backwards-compatible bug fixes.

Additional labels for pre-release and build metadata are available as extensions to the `MAJOR.MINOR.PATCH` format.

See the [Semantic Versioning](http://semver.org/) specification for more information.

## Deploying to Production

1. Run `grunt build` to make sure you are deploying the latest version of the project
1. Copy all files within the `dist/` folder into the root of `http://media.domain.com`
2. Create a `files/` folder also on the root of `http://media.domain.com`
3. Grant the `files/` folder appropriate write permissions:

```
mkdir files
chmod 755 files
sudo chown www-data:www-data files
```

**Note:** `www-data` is the user Apache runs under. You might need to change it depending on your server's config.
