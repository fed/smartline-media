# Smartline Media Server API

![Smartline](http://i.imgur.com/UHyyc4e.png)

## Deploy

1. Copy all files within the `src/` folder into the root of `http://media.domain.com`
2. Create a `files/` folder also on the root of `http://media.domain.com`
3. Grant the `files/` folder appropriate write permissions:

```
mkdir files
chmod 755 files
sudo chown www-data:www-data files
```

**Note:** `www-data` is the user Apache runs under. You might need to change it depending on your server's config.

## Usage

To serve images:

```
./serve.php?id=<IMAGE HASH>
./serve.php?id=<IMAGE HASH>&width=<WIDTH>
./serve.php?id=<IMAGE HASH>&width=<WIDTH>&height=<HEIGHT>
```

To upload images:

```
<form method="POST" action="upload.php" enctype="multipart/form-data">
  <input type="file" name="image" />
  <input type="submit" value="Upload" />
</form>
```
