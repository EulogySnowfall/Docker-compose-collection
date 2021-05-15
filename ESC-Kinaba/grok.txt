NGINX ACCESS LOGS

1.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/nginx/access.log

2.

https://grokdebug.herokuapp.com/

3.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/nginx/nginx-access-final.conf

4.

curl -XGET "http://localhost:9200/nginx-access-logs-02/_search?pretty" -d'{
  "size": 1, 
  "track_total_hits": true,
  "query": {
    "bool": {
      "must_not": [
        {
          "term": {
            "tags.keyword": "_grokparsefailure"
          }
        }
      ]
    }
  }
}'

IIS LOGS

5.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/iis/u_ex171118-sample.log

6.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/iis/iis-final-working.conf

7.

curl -XGET "http://localhost:9200/iis-log/_search?pretty" -d'{
  "size": 1, 
  "track_total_hits": true,
  "query": {
    "bool": {
      "must_not": [
        {
          "term": {
            "tags.keyword": "_grokparsefailure"
          }
        }
      ]
    }
  }
}'

MONGODB LOGS

8. 

https://raw.githubusercontent.com/coralogix-resources/logstash/master/mongodb/mongodb.log

9.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/mongodb/mongodb-final.conf

10.

curl -XGET "http://localhost:9200/mongo-logs-01/_search?pretty" -d'{
  "size": 1, 
  "track_total_hits": true,
  "query": {
    "bool": {
      "must_not": [
        {
          "term": {
            "tags.keyword": "_grokparsefailure"
          }
        }
      ]
    }
  }
}'

USER AGENT MAPPING AND IP TO GEO LOCATION MAPPING IN LOGS

11.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/apache/access_log

12.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/apache/apache-access-enriched.conf

13.

curl -XGET "http://localhost:9200/apache-logs/_search?pretty" -d'{
  "size": 1,
  "track_total_hits": true,
  "query": {
  "bool": {
    "must_not": [
      {
        "term": {
          "tags.keyword": "_grokparsefailure"
        }
      }
    ]
  }
  }
}'

ELASTICSEARCH LOGS

14.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/elasticsearch_logs/elasticsearch.log

15.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/elasticsearch_logs/es-logs-final.conf

16.

curl -XGET "http://localhost:9200/es-test-logs/_search?pretty" -d'{
  "size": 1, 
  "query": {
    "bool": {
      "must_not": [
        {
          "match": {
            "tags": "multiline"
          }
        }
      ]
    }
  }
}'

ELASTICSEARCH SLOW LOGS

17.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/elasticsearch_slowlogs/es_slowlog.log

18.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/elasticsearch_slowlogs/es-slowlog-final.conf

19.

curl -XGET "http://localhost:9200/es-slow-logs/_search?pretty" -d'{  "size": 1}'

MYSQL SLOW LOGS

20.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/mysql_slowlogs/mysql-slow.log

21.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/mysql_slowlogs/mysql-slowlogs.conf

22.

curl -XGET "http://localhost:9200/mysql-slowlogs-01/_search?pretty" -d'{

  "size":1,
  "query": {
    "bool": {
    "must_not": [
      {
        "term": {
          "tags.keyword": "_grokparsefailure"
        }
      }
    ]
  }
  }

}'

AWS ELASTIC LOAD BALANCER

23.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/aws_elb/elb_logs.log

24.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/aws_elb/aws-elb.conf

25.

curl -XGET "http://localhost:9200/aws-elb-logs/_search?pretty" -d'
{
  "size": 1,
  "query": {
    "bool": {
      "must_not": [
        {
        "term": {
          "tags": {
            "value": "_grokparsefailure"
          }
        }
      }
      ]
    }
  }
}'

AWS APPLICATION LOAD BALANCER

26.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/aws_alb/alb_logs.log

27.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/aws_alb/aws-alb.conf

28.

curl -XGET "http://localhost:9200/aws-alb-logs/_search?pretty" -d'
{
  "size": 1,
  "query": {
    "bool": {
      "must_not": [
        {"term": {
          "tags": {
            "value": "_grokparsefailure"
          }
        }
      }
      ]
    }
  }
}'

AWS CLOUDFRONT

29.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/aws_cloudfront/cloudfront_logs.log

30.

https://raw.githubusercontent.com/coralogix-resources/logstash/master/aws_cloudfront/aws-cloudfront.conf

31.

curl -XGET "http://localhost:9200/aws-cloudfront-logs/_search?pretty" -d'
{
  "query": {
    "bool": {
      "must_not": [
        {"term": {
          "tags": {
            "value": "_grokparsefailure"
          }
        }
      }
      ]
    }
  }
}'

CLEANUP

curl -XDELETE localhost:9200/nginx-access-logs-02

curl -XDELETE localhost:9200/iis-log

curl -XDELETE localhost:9200/mongo-logs-01

curl -XDELETE localhost:9200/apache-logs

curl -XDELETE localhost:9200/es-test-logs

curl -XDELETE localhost:9200/es-slow-logs

curl -XDELETE localhost:9200/mysql-slowlogs-01

curl -XDELETE localhost:9200/aws-elb-logs

curl -XDELETE localhost:9200/aws-alb-logs

curl -XDELETE localhost:9200/aws-cloudfront-logs
