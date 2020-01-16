<?php
  include('includes/test.php');
  if (!isset($_GET['id']) || !file_exists('/videos/test-' . $_GET['id'] . '.json')) {
    echo "This is not a valid run";
    exit;
  }
  $info = json_decode(file_get_contents('/videos/test-' . $_GET['id'] . '.json'), TRUE);
  if (!isset($info['corrupt']) || $info['corrupt']) {
    echo "This encoding is totally corrupt";
    exit;
  }
  $test = new test();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Encoder Information</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1>Encoder Information</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <h2>Actual Video</h2>
          <video width="100%" controls>
            <source src="/videos/test-<?php echo $_GET['id']; ?>.mp4" type="video/mp4">
          Your browser does not support the video tag.
          </video>
          <a href="" class="btn btn-primary btn-lg btn-block" href=/videos/test-<?php echo $_GET['id']; ?>.mp4" download="test-<?php echo $_GET['id']; ?>.mp4">Download Video</a>
          <hr/>
          <h2>Mediainfo</h2>
          <hr/>
            <?php foreach ($info['mediainfo'] as $header => $data) { ?>
              <h5><?php echo $header; ?></h5>
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                  <th scope="col" style="width: 33.33%">Key</th>
                  <th scope="col" style="width: 33.33%">Value</th>
                  <th scope="col" style="width: 33.33%">Test Value</th>
                </thead>
                <tbody>
                <?php foreach ($data as $key => $value) { ?>
                  <?php echo $test->testValue($header, $key, $value); ?>
                <?php } ?>
                </tbody>
                </table>
            <?php } ?>
            <hr/>
            <h2>Frame-by-Frame Information</h2>
            <hr/>
            <?php foreach($info['ffprobe'] as $header => $data) { ?>
              <h5><?php echo ucfirst($header); ?> Streams</h5>
              <?php foreach($data as $stream_id => $stream_data) { ?>
                <h6>Stream #<?php echo $stream_id; ?></h6>
                <div class="table-frame">
                  <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                    <th scope="col" style="width: 20%">Frame #</th>
                    <th scope="col" style="width: 20%">Frame Type</th>
                    <th scope="col" style="width: 20%">PTS Time</th>
                    <th scope="col" style="width: 20%">DTS Time</th>
                    <th scope="col" style="width: 20%">Detail Info</th>
                  </thead>
                  <tbody>
                  <?php foreach($stream_data as $key => $frame) { ?>
                    <tr id="<?php echo $header . '-' . $stream_id . '-' . $key; ?>">
                      <?php
                        $frame_type = isset($frame['pict_type']) ? $frame['pict_type'] : $frame['media_type'];
                        if ($frame_type == 'I') {
                          $frame_type = 'Key Frame (I)';
                        }
                      ?>
                      <td><?php echo ($key+1); ?></td>
                      <td><?php echo $frame_type ?></td>
                      <td><?php echo $frame['pkt_pts_time']; ?></td>
                      <td><?php echo $frame['pkt_dts_time']; ?></td>
                      <td class="frame-info" data-id="<?php echo $header . '-' . $stream_id . '-' . $key; ?>">Detail Info</td>
                    </tr>
                  <?php } ?>
                  </tbody>
                  </table>
                </div>
                <hr />
              <?php } ?>
            <?php } ?>

            <div class="modal" id="frameModal">
              <div class="modal-dialog">
                <div class="modal-content">

                  <div class="modal-header">
                    <h4 class="modal-title">Frame Info</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <div class="modal-body">
                    Information
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                </div>
              </div>
            </div>
            <script>
              var info=<?php echo json_encode($info); ?>;
            </script>
        </div>
      </div>
      <script src="/js/info-logic.js"></script>
  </body>
</html>
