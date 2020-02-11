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
          <button type="button" id="test-srt" disabled class="btn btn-primary btn-lg btn-block">SRT Listener</button>
          <button type="button" id="test-rtmp" disabled class="btn btn-primary btn-lg btn-block">RTMP Push</button>
          <button type="button" id="test-rtmp-pull" disabled class="btn btn-primary btn-lg btn-block">RTMP Pull</button>
          <button type="button" id="test-hls-pull" disabled class="btn btn-primary btn-lg btn-block">HLS Pull</button>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-12 text-center">
          This project is created by <a href="https://oskberlin.com/" target="_blank">OSK Berlin</a> and is licensed under the GPLv3 license and is provided as is without warranty.
        </div>
      </div>
      <div class="modal" id="frameModal">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title">Frame Info</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
              <div class="form-group">
                <label for="url">URL*</label>
                <input type="text" class="form-control" id="url" aria-describedby="urlHelp" />
                <div id="urlHelp"></div>
              </div>
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" aria-describedby="usernameHelp" />
                <small id="usernameHelp" class="form-text text-muted">Only add if you want to bypass basic auth</small>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" aria-describedby="passwordHelp" />
                <small id="passwordHelp" class="form-text text-muted">Only add if you want to bypass basic auth</small>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" id="validate-stream" class="btn btn-primary">Validate</button>  
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

          </div>
        </div>
      </div>
      <script src="/js/list-logic.js"></script>
  </body>
</html>
