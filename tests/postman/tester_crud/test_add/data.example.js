/**
 * The data here is provided to the test on a row by row basis.
 * Newman/Postman does not currently support this feature but we are working with them directly
 * to get this in play.
 *
 * The plan is that we can pass rows of data like this, and execute the tests once per row.
 *
 * Example JSON data to place in file data.json
		[
			{
				"username": "someone",
				"password": "somepass",
				"valueA": "12345",
				"rfrs": [
					{
						"id": 1234,
						"name": "First RFR"
					},
					{
						"id": 1235,
						"name": "Second RFR"
					}
				]
			},
			{
				"username": "someone",
				"password": "somepass",
				"valueA": "12001",
				"rfrs": [
					{
						"id": 1010,
						"name": "First RFR"
					},
					{
						"id": 1011,
						"name": "Second RFR"
					}
				]
			}
		]
 */