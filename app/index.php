<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Encoder Validator</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.js"></script>
    <script src="/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1>OSK Encoder Validator</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-9">
          <table class="table table-hover table-striped" id="result-set">
            <thead>
              <tr>
                <th scope="col">Test #</th>
                <th scope="col">Date/Time</th>
                <th scope="col">More info</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan=3>Checking for runs...</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-lg-3 jumbotron">
          <div id="status-information">
            <h1>Status</h1>
            <h4>Current status</h4>
            <p id="message">Checking...</p>
          </div>
          <button type="button" id="cancel-test" class="btn btn-danger btn-lg btn-block">Cancel test/listening</button>
          <button type="button" id="test-srt" disabled class="btn btn-primary btn-lg btn-block">Test using SRT</button>
          <button type="button" id="test-rtmp" disabled class="btn btn-primary btn-lg btn-block">Test using RTMP</button>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-12 text-center">
          This project is created by <a href="https://oskberlin.com/" target="_blank">OSK Berlin</a> and is licensed under the GPLv3 license and is provided as is without warranty.
        </div>
      </div>
      <script src="/js/list-logic.js"></script>
  </body>
</html>
