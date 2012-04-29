Object/Key Operations
=====================

Riak is in essence a key-value database. The ideas is that we can store a value and refer to it by its key.

Most of the interactions you’ll have with Riak will be setting or retrieving the value of a key. This section describes how to do that.

Read an Object
--------------

Here is the basic command formation for retrieving a specific key from a bucket.

`GET /riak/bucket/key`

The body of the response will be the contents of the object (if it exists). Simple, right?
