# test-task-2
To complete task was used PHP Symfony4 framework, with phpStorm and XAMPP 

## Instalation
Instalation is not different from any other Symfony4 application just upload files to your server and you are ready to go

## Summary 
I completed all three requiruments

1.Prased (aka crawled) data is stored in jobs.json file in public directory it all checked with schema.json file with JsonSchema\Validator help. 
*To complete this sub-task I created few services that i called helpers WebCrawlerHelper.php to crawl the web, SchemaJsonHelper.php to validate with schema.json and FileHelper.php to save file*

2. In [your-url]/show we can see json file from jobs.json file
*to complete this subtask i created open function in FileHelper.php file class*

3. with Command line interface command "php bin/console check-json" we can check
*To complete this subtask i created Command/CheckJson.php file with command I used previous mentioned WebCrawlerHelper.php with FileHelper.php files classes*

* for one of bonus subtasks i created WebCrawlerTest.php test class with three test cases for everyone of WebCrawler.php controller method 
