// Generated information
const restletUrl = '';
const accountNumber = '';
const consumerKey = '';
const consumerSecret = '';
const tokenKey = '';
const tokenSecret = '';

// Dependencies
const request = require('request');
const OAuth = require('oauth-1.0a');
const crypto = require('crypto');

// Initialize
const oauth = OAuth({
  consumer: {
    key: consumerKey,
    secret: consumerSecret
  },
  realm: accountNumber,
  signature_method: 'HMAC-SHA1',
  hash_function(base_string, key) {
    return crypto.createHmac('sha1', key).update(base_string).digest('base64');
  }
});

const requestData = {
  url: restletUrl,
  method: 'GET'
};

// Note: The token is optional for some requests
const token = {
  key: tokenKey,
  secret: tokenSecret
};

request({
  url: requestData.url,
  method: requestData.method,
  headers: oauth.toHeader(oauth.authorize(requestData, token)),
}, function(error, response, body) {
  console.log(body);
});
