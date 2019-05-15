# Cbeds Test
 ### Requirements:
 - User is able to specify price depending on the dates. i.e. set the price $x for the
 - dates range $startDate - $endDate. Let’s call such ranges as intervals.
 - User can add as many intervals as he wants (using any start/end date).
 - System can’t have crossing intervals.
 - New interval price have higher priority over existing ones.
 - New interval can’t lead to changes in dates not belonged to its dates range.
 - If user tries to save interval that interferes with existing ones in DB, system has to apply last user changes and modify other  intervals in order to apply requirement 3.
 - Any intervals with the same price that can be merged (without gaps between) should be merged.

 ### Demo:
Demo deployed here http://192.241.207.23 for manual testing

 ### Instructions:
* Install DB dump file on your database server `cbeds_intervals.sql` located under the db folder 
* Edit the `dbconfig.ini` file with your DB Connection information
 ### Api endpoints:
 Method | Endpoint | Fields | Description |
------|------------|------|----------|
GET | /api |  |Get all intervals |
POST | /api | date_start, date_end, price | Save an interval |
PUT | /api | date_start, date_end, price | Updates an interval |
POST | /api/cleardb | | Clears intervals DB table |
DELETE | api | id | Deletes an interval |
