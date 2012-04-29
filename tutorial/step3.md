Store an object with existing or user-defined key
==

Your application will often have its own method of generating the keys for its data. If so, storing that data is easy. The basic request looks like this.

Url: `/riak/songs/razzmatazz`

Method: `PUT`

Header: `Content-Type: application/json`

Data: `{"author":"pulp"}`

