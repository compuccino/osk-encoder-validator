$(document).ready(function() {
  checkStatus(true);
  checkList();
  $('#test-srt').on('click', function() {
    testSrt();
  });
  $('#test-rtmp').on('click', function() {
    testRtmp();
  });
  $('#test-rtmp-pull').on('click', function() {
    modalRtmpPull();
  });
  $('#test-hls-pull').on('click', function() {
    modalHlsPull();
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

function modalHlsPull() {
  pullModal();
  $('#validate-stream').off('click').on('click', testHlsPull);
}

function modalRtmpPull() {
  pullModal();
  $('#validate-stream').off('click').on('click', testRtmpPull);
}

function testHlsPull() {
  if ($('#url').val() == '') {
    $('#urlHelp').addClass('invalid-feedback');
    $('#url').addClass('is-invalid');
    $('#urlHelp').html('Please give a valid HLS stream.')
  } else {
    $.ajax({
      url: "/hls-pull.php?url=" + $('#url').val() + '&username=' + $('#username').val() + '&password=' + $('#password').val(),
      error: function(){
        $('#frameModal').modal('hide');
      },
      success: function(){
        $('#frameModal').modal('hide');
      },
      timeout: 100
    });
  }
}

function testRtmpPull() {
  if ($('#url').val() == '') {
    $('#urlHelp').addClass('invalid-feedback');
    $('#url').addClass('is-invalid');
    $('#urlHelp').html('Please give a valid RTMP stream.')
  } else {
    $.ajax({
      url: "/rtmp-pull.php?url=" + $('#url').val() + '&username=' + $('#username').val() + '&password=' + $('#password').val(),
      error: function(){
        $('#frameModal').modal('hide');
      },
      success: function(){
        $('#frameModal').modal('hide');
      },
      timeout: 100
    });
  }
}

function pullModal() {
  $('#urlHelp').html('')
  $('#urlHelp').removeClass('invalid-feedback');
  $('#url').removeClass('is-invalid');
  $('#url').val('');
  $('#username').val('');
  $('#password').val('');
  $('#frameModal').modal('show');
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
    for (row of json.list) {
      var link = '/info.php?id=' + row.number;
      html = html + '<tr><td>' + row.number + '</td><td>' + row.date + '</td><td>';
      html = html + '<div class="btn-group" role="group" aria-label="Button group with nested dropdown"><button type="button" class="btn btn-secondary"><a href="' + link + '">More Info</a></button>';
      html = html + '<div class="btn-group" role="group"><button id="btnGroupDrop' + row.number + '" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
      html = html + '</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop' + row.number + '">';
      for (validation of json.validations) {
        html = html + '<a class="dropdown-item" href="' + link + '&validate=' + validation + '">Test against <strong>' + validation + '</strong></a>';
      }
      html = html + '</div></div></div></td></tr>';
    }
    $('#result-set tbody').html(html);
    setTimeout(checkList, 20000);
  });
}

function checkStatus(first) {
  if (typeof first === 'undefined') {
    first = false;
  }
  $.getJSON("/status.php", function( json ) {
    if (json.listening === false && json.running === false) {
      nonActiveStream();
      $('#message').html('Ready to start listening/pulling. Click the streaming protocol you want to use.');
    }
    if (json.listening === true && json.running === false && json.listening_format == 'srt') {
      activeStream();
      $('#message').html('Listening to srt on <strong>' + window.location.hostname + ':6872</strong> to passcode <strong>1234567890123456</strong>. Please stream to this when ready.');
    }
    if (json.listening === true && json.running === false && json.listening_format == 'rtmp') {
      activeStream();
      $('#message').html('Listening to rtmp on <strong>rtmp://' + window.location.hostname + ':6872</strong>. Please stream to this when ready.');
    }
    if (json.listening === true && json.running === true) {
      activeStream();
      $('#message').html('Currently streaming/reading. It will run for 30 seconds before we validate the stream.');
    }
    if (json.listening === false && json.running === true) {
      activeStream();
      $('#cancel-test').attr('disabled', true);
      $('#message').html('Processing your stream. This may take some seconds. Check the test list for updates.');
    }
    if (first) {
      $('#test-rtmp').attr('disabled', false);
      $('#test-rtmp-pull').attr('disabled', false);
      $('#test-hls-pull').attr('disabled', false);
      $('#test-srt').attr('disabled', false);
    }
    setTimeout(checkStatus, 500);
  });
}

function activeStream() {
  $('#cancel-test').attr('disabled', false);
  $('#test-srt').hide();
  $('#test-rtmp').hide();
  $('#test-rtmp-pull').hide();
  $('#test-hls-pull').hide();
  $('#cancel-test').show();
}

function nonActiveStream() {
  $('#cancel-test').attr('disabled', false);
  $('#test-srt').show();
  $('#test-rtmp').show();
  $('#test-rtmp-pull').show();
  $('#test-hls-pull').show();
  $('#cancel-test').hide();
}