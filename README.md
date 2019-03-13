# WP REST API extra routes for post meta

Extra routes for WP REST API2 to manage post meta data.
Now, only one route to get pair list of specific meta data and post id.

Requires WP API Version 2.0 Beta 13.1 or higher.

## Usage

```
$ curl --user "$username:$password" -X GET http://example.com/wp-json/meta_val/v1/posts/some_param/limit/-1/offset/0 | jq
[
  {
    "some_param": "34",
    "post_id": 24689
  },
  {
    "some_param": "33",
    "post_id": 26826
  },
  ...
]
```
