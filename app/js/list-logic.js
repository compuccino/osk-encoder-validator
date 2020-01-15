$(document).ready(function() {
  checkStatus(true);
  checkList();
  $('#test-srt').on('click', function() {
    testSrt();
  });
  $('#test-rtmp').on('click', function() {
    testRtmp();
  });
  $('#cancel-test').on('click', function() {
    cancelTest();
  })
});

function testSrt() {
  activeStream();
  $.ajax({
    url: "/srt.php",
    error: function(){
    },
    success: function(){
    },
    timeout: 100
  });
}

function testRtmp() {
  activeStream();
  $.ajax({
    url: "/rtmp.php",
    error: function(){
    },
    success: function(){
    },
    timeout: 100
  });
}

function cancelTest() {
  $('#cancel-test').attr('disabled', true);
  $.ajax({
    url: "/cancel.php",
    error: function(){
    },
    success: function(){
    },
    timeout: 100
  });
}

function checkList() {
  $.getJSON("/list.php", function( json ) {
    var html = '';
    for (row of json) {
      html = html + '<tr><td>' + row.number + '</td><td>' + row.date + '</td><td><a href="info.php?id=' + row.number + '">More info</a></td>';
    }
    $('#result-set tbody').html(html);
    setTimeout(checkList, 5000);
  });
}

function checkStatus(first) {
  if (typeof first === 'undefined') {
    first = false;
  }
  $.getJSON("/status.php", function( json ) {
    if (json.listening === false && json.running === false) {
      nonActiveStream();
      $('#message').html('Ready to start listening. Click the streaming protocol you want to use.');
    }
    if (json.listening === true && json.running === false && json.listening_format == 'srt') {
      activeStream();
      $('#message').html('Listening to srt on port <strong>6872</strong> to passcode <strong>1234567890123456</strong>. Please stream to this when ready.');
    }
    if (json.listening === true && json.running === false && json.listening_format == 'rtmp') {
      activeStream();
      $('#message').html('Listening to rtmp on port <strong>6872</strong>. Please stream to this when ready.');
    }
    if (json.listening === true && json.running === true) {
      activeStream();
      $('#message').html('Currently streaming. Let it run for 30 seconds before we validate the stream.');
    }
    if (json.listening === false && json.running === true) {
      activeStream();
      $('#cancel-test').attr('disabled', true);
      $('#message').html('Processing your stream. This may take some seconds. Check the test list for updates.');
    }
    if (first) {
      $('#test-rtmp').attr('disabled', false);
      $('#test-srt').attr('disabled', false);
    }
    setTimeout(checkStatus, 500);
  });
}

function activeStream() {
  $('#cancel-test').attr('disabled', false);
  $('#test-srt').hide();
  $('#test-rtmp').hide();
  $('#cancel-test').show();
}

function nonActiveStream() {
  $('#cancel-test').attr('disabled', false);
  $('#test-srt').show();
  $('#test-rtmp').show();
  $('#cancel-test').hide();
}