var elasticsearch = require('elasticsearch');

/**
 * Create elasticsearch connection
 */
var client = new elasticsearch.Client({
    host: 'localhost:9200',
    log: 'error'
});

module.exports = client;
