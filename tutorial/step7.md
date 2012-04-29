Delete an object
==

Lastly, you’ll need to know how to delete keys.

The command, as you can probably guess, follows a predictable pattern and looks like this:

URL: `/riak/bucket/key`

METHOD: `DELETE`

The normal response codes for **DELETE** operations are **204 No Content** and **404 Not Found**

404 responses are "normal" in the sense that DELETE operations are idempotent and not finding the resource has the same effect as deleting it.
