|              URL              | HTTP method |        JSON Response       |
|:-----------------------------:|:-----------:|:--------------------------:|
| /books                        | POST        | create book                |
| /categories                   | POST        | create category            |
| /publishers                   | POST        | create publisher           |
| /writers                      | POST        | create writer              |
| /books                        | GET         | all books                  |
| /books/{id}                   | GET         | specific book              |
| /categories                   | GET         | all categories             |
| /categories/{id}              | GET         | specific category          |
| /publishers                   | GET         | all publishers             |
| /publishers/{id}              | GET         | specific publisher         |
| /writers                      | GET         | all writers                |
| /writers/{id}                 | GET         | specific writer            |
| /categories/{category}/books  | GET         | specific category's books  |
| /publishers/{publisher}/books | GET         | specific publisher's books |
| /writers/{writer}/books       | GET         | specific writer's books    |
| /books/{id}                   | PUT         | edit specific book         |
| /categories/{id}              | PUT         | edit specific category     |
| /publishers/{id}              | PUT         | edit specific publisher    |
| /writers/{id}                 | PUT         | edit specific writer       |
| /books/{id}                   | DELETE      | delete specific book       |
| /categories/{id}              | DELETE      | delete specific category   |
| /publishers/{id}              | DELETE      | delete specific publisher  |
| /writers/{id}                 | DELETE      | delete specific writer     |