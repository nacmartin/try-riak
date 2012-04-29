Store a new object and assign random key
==

If your application would rather leave key-generation up to Riak, issue a POST request to the bucket URL instead of a PUT to a bucket/key pair:

Url: `/riak/songs`

Method: `POST`

Header: `Content-Type: application/json`

Data: `{"foo":"Bar"}`

If you don’t pass Riak a “key” name after the bucket, it will know to create one for you.

Supported headers are the same as for bucket/key PUT requests. Supported query parameters are also the same as for bucket/key PUT requests.
