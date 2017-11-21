# Smartline Media Server API

![Smartline](https://i.imgur.com/UHyyc4e.png)

## Usage

To serve images:

```
./serve.php?id=<FILENAME>
./serve.php?id=<FILENAME>&width=<WIDTH>
./serve.php?id=<FILENAME>&width=<WIDTH>&height=<HEIGHT>
```

If the server supports htaccess redirection:

```
<HOSTNAME>/image/<FILENAME>
<HOSTNAME>/image/<FILENAME>/width/<WIDTH>
<HOSTNAME>/image/<FILENAME>/width/<WIDTH>/height/<HEIGHT>
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

1. Copy all files within the `src/` folder into the root of `http://media.domain.com` (make sure to also copy the hidden `.htaccess` file)
2. Create a `files/` folder also on the root of `http://media.domain.com`
3. Grant the `files/` folder appropriate write permissions:

```
mkdir files
chmod 755 files
sudo chown www-data:www-data files
```

**Note:** `www-data` is the user Apache runs under. You might need to change it depending on your server's config.

## Updating Media Server URL

Run this query:

```sql
UPDATE media
  SET url = REPLACE(url, 'http://smartline-media.argendev.com', 'https://media.smartline.argendev.com')
  WHERE type = 'image'
```
