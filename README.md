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
| `npm start`       | Watch for changes on .php files  |
| `npm run build`   | Build all the things!            |

## Usage

To serve images:

```
./serve.php?id=<FILENAME>
./serve.php?id=<FILENAME>&width=<WIDTH>
./serve.php?id=<FILENAME>&width=<WIDTH>&height=<HEIGHT>
```

To upload images:

```html
<form method="POST" action="upload.php" enctype="multipart/form-data">
  <input type="hidden" name="apiKey" value="<API_KEY>">
  <input type="file" name="image" />
  <input type="submit" value="Upload" />
</form>
```

You need to `POST` to the upload endpoint, being `image` and `apiKey` the fields you need to populate:

![Uploading pictures using Postman](http://i.imgur.com/u3ThDd1.png)

From the client using [axios](https://github.com/mzabriskie/axios):

```js
// Needs be sent as `multipart/form-data`
const data = new FormData();

data.append('apiKey', MEDIA_SERVER_API_KEY);
data.append('image', file);

axios
  .post(media, data)
  .then((response) => console.log(response.data));
```

where `file` here is an [HTML5 file](https://developer.mozilla.org/en/docs/Web/API/File) -- which should be an image.

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

## Updating Media Server URL

```sql
UPDATE media
	SET url = REPLACE(url, 'http://media.freyre.com.ar', 'https://smartline-media.argendev.com')
	WHERE type = 'image'
```
