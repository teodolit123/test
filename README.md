mircoservise for task.
Background:
You work for a fashion e-commerce company. In the fashion industry it is usual that products of
different sizes have the same price. For example, shoes of size 36 have the same price as
shoes of size 45 of the same model. However, some categories of fashion can have different
prices depending on the size/weight/length. Example: perfumes (same perfume sold in bottles
of different volume), jewelry (the more the length of the gold bracelet the more it costs).
Task:
● You need to develop a REST API which will allow users to update or create prices of
products.
● Price information contains price, currency, name of the size/variant of product (eg: XS,
41), name of the product, category.
● The price must be created only if the product has no price in the database.
● Categories are limited to Shoes (same price for all sizes should be applied, when price
for one size is set, other sizes should be updated to new price) and Jewelry (sizes can
have different prices).
● Your API should perform just a single task: accept price information for a single product
and save it to the database.
Expected result:
● Public github repository or ZIP file of git repo including commit history with instructions
how to run created API.
● You can use any way of running your API (Docker is preferred but you can use a built-in
web-server of PHP, Vagrant, whatever else you find suitable).
● You can use any database or storage that you find suitable for this task.
● Key thing is that users will be able start your application and the API works according to
task specification.