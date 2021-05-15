# ElasticSearch

## Create index with json

```bash
curl -H "Content-Type: application/json" -XPUT 127.0.0.1:9200/shakespeare --data-binary @shakes-mapping.json
```

## Put data in index

```bash
curl -H "Content-Type: application/json" -XPOST 127.0.0.1:9200/shakespeare/_bulk --data-binary @shakespare_7.0.json
```

## Search

```bash
curl -H "Content-Type: application/json" -XGET 127.0.0.1:9200/shakespeare/_search?pretty  -d '{"query" : {"match_phrase" : {"text_entry" : "to be or not to be"}}}'

curl -H "Content-Type: application/json" -XGET '127.0.0.1:9200/movies/_search?pretty&q=Dark' 
```

## Get a document

```bash
curl -H "Content-Type: application/json" -XGET 127.0.0.1:9200/movies/_doc/109487?pretty 
```
*** this command in kibana update the doc ***


## Update document

Kibana
  POST movies/_update/109488
{
  "doc": {
    "title": "movie Test wow"
  }
}
With seq to avoid conflict
```bash
curl -H "Content-Type: application/json" -XPUT '127.0.0.1:9200/movies/_doc/109487?if_seq_no=20&if_primary_term=1' -d '
{
   "genre" : [
      "IMAX",
      "Sci-Fi"
    ],
    "title" : "Interstellar 2",
    "year" : 2014
}'

curl -H "Content-Type: application/json" -XPOST '127.0.0.1:9200/movies/_update/109487?retry_on_conflict=5' -d '
{
   "doc":{
        "title" : "Interstellar"
    }
}'


```


## Delete document

```bash
curl -H "Content-Type: application/json" -XDELETE 127.0.0.1:9200/movies/_doc/109487
```

## Exceptions
- No respect of mapping

### Close index

```bash
curl -H "Content-Type: application/json" -XPOST 127.0.0.1:9200/movies/_close
```

### Change mapping
After close
Ignore malformed
```bash
curl -H "Content-Type: application/json" -XPUT 127.0.0.1:9200/movies/_settings -d '{
  "index.mapping.ignore_malformed": true
}'
```

### Open index
reopen index
```bash
curl -H "Content-Type: application/json" -XPOST 127.0.0.1:9200/movies/_open
```

- Wrong json format

### payload field

Can be use for non formated attented field

```bash
curl -H "Content-Type: application/json" -XPOST 'http://localhost:9200/microservice-logs/_doc?pretty' \
--data-raw '{"timestamp": "2020-04-11T12:34:56.789Z", "service": "ABC", "host_ip": "10.0.2.15", "port": 12345, "message": "Received...", "payload": {"data": {"received":"here"}}}'
```

or use DLQ

### Can add more then 1000 auto-generate fields name auto mapped

```bash
# Will grate a 1001 json keys with his item
thousandone_fields_json=$(echo {1..1001..1} | jq -Rn '( input | split(" ") ) as $nums | $nums[] | . as $key | [{key:($key|tostring),value:($key|tonumber)}] | from_entries' | jq -cs 'add')
 
echo "$thousandone_fields_json"

curl -H "Content-Type: application/json" -XPUT 'http://localhost:9200/big-objects'

curl -H "Content-Type: application/json" -XPOST 'http://localhost:9200/big-objects/_doc?pretty' -d '$thousandone_fields_json'
## marche pas en 7.4??

change index limite "index.mapping.total_field": 1001
```


## Query lite

/movies/_search?q=title:star

- need to be URL encode
/movies/_search?q=+year:>2010+title:trek


# Query/filter type

Filter : filter as yes or no bool type if find or not
Query : Will score the search value. The more score you have, higher your are

- filter
 - term = exact
 - terms = if any exact
 - range = in a range of time of number range ip interger
 - exists = field exist
 - missing - field missing
 - bool combine filter 
  - must : and
  - must_not: not
  - should: or

- Queryes: (score the result)
 - match_all
 - match
 - multi_match with query in, will score the multi-match 

filter can be inside query and query can be inside filter. 
- Exemple complexe
{
  "query": 
}


# match_phrase

- parameters
 - slop: 1 #  distance of 1 word close together. can be 100, so this can be in deferent place in the phrase. but will be score proximaty

need to be in a query
 "match_phrase": {"title" {"query": "star beyond", "slop":1}}


 # from/size (pagination)
{
  "query"{

  },
  "from": 0, 
  "size": 2
}

<http://localhost:9200/movies/_search?from=2&size=2&pretty>

# sorting
- for integer
<http://localhost:9200/movies/_search?sort=year&pretty>


- for text we can use sub field in mapping

{
"mappings":{
  "properties":{
    "title": {
      "type": "text".
      "fields" {
        "raw":{
          "type": "keyword"
        }
      }
    }
  }
}
}

- text used for analyse and keyword used for sort
<http://localhost:9200/movies/_search?sort=title.raw&pretty>


# Fuzzy matches (typo)

substitutions/insertions/deletion (caractere).

more you have, least score 

we can specifie tolerance
{
  "query":{
    "fuzzy": {
      "title" {"value": "intersteller", "fuzziness": 1}
    }
  }
}

- AUTO
0 for 1-2 car
1 for 3-5 car
2 for more

## Partial Match (can be use on text)
- prefix
{
  "query":{
    "prefix"{
      "year": "201"
    }
  }
}

- wildcard
{
  "query":{
    "wildcard"{
      "year": "1*"
    }
  }
}

## Search as you type (google style)
- can be use in text
{
  "query":{
    "match_phrase_prefix":{
      "title": {
        "query":{
          "title": "star t"
          "slop": 10
        }
      }
    }
  }
}

### N-gram (sayt.txt) <---- Google search style

 - unigram [s,t,a,r]
 - bigram [st, ta, ar]
 - trigram [sta, tar]
 - 4-gram [star]

#### Custom analyser

- create analyser
{
  "settings":{
    "analysis":{
      "filter": {
        "autocomplete_filter":{
          "type": "edge_ngram",
          "min_gram": 1,
          "max_gram": 20
        }
      },
      "analyser":{
        "autocomplete":{
          "type": "custom"
          "tokenizer": "standard"
          "filter":[
            "lowercase",
            "autocomplete_filter"
          ]
        }
      }
    }
  }
}

- apply (mapping)
{
  "properties" : {
    "title": {
      "type": "text",
      "analyser": "autocomplete"
    }
  }
}
- in query (select analyser on filt)
{
  "query" {
    "match" {
      "title":{
        "query": "sta",
        "analyser": "standard"
      }
    }
  }
}

## Reindex (reindex in new index from a other)

```bash
curl --silent --request POST 'http://localhost:9200/_reindex?pretty' --data-raw '{
 "source": {
   "index": "movies"
 },
 "dest": {
   "index": "autocomplete"
 }
}' | grep "total\|created\|failures"
```

## Logstach

<https://www.elastic.co/guide/>

```bash 
sudo apt install openjdk-8-jre-headless
sudo apt update
sudi apt install logstach

## Check Docker-compose
```
### jdbc input (config exemple)

- jar connector
<https://dev.mysql.com/downloads/connector/j/>

Check vidéo 54 on ES7 course

```config
input {
  jdbc {
    jdbc_connection_string => "jdbc:mysql://localhost:3306/movielens"
    jdbc_user => "student"
    jdbc_password => "password"
    jdbc_driver_library => "/data/mysql-connector/mysql-connector-java-$ver.jar"
    jdbc_driver_class => "com.mysql.jdbc.Driver"
      statement => "SELECT * FROM movies"
  }
}
```
### CSV

- Data exemple
```bash
curl -O https://raw.githubusercontent.com/coralogix-resources/elk-course-samples/master/csv-schema-short-numerical.csv
``` 
- logstach config
```
curl -O https://raw.githubusercontent.com/coralogix-resources/elk-course-samples/master/csv-read.conf
```

- logstach convert
```
curl -O https://raw.githubusercontent.com/coralogix-resources/elk-course-samples/master/csv-read-drop.conf
```

### json

- Exemple log
  sample-josn.log

- Config base
  config-lohstash/read-json.conf

- Config drop & remove
  config-lohstash/read-json-drop.conf