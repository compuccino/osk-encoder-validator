$(document).ready(function() {
  $('.frame-info').on('click', function() {
    var html = '';
    var dataId = $(this).attr('data-id');
    var parts = dataId.split('-');
    var type = parts[0];
    var stream = parts[1];
    var key = parts[2];
    for (x in info.ffprobe[type][stream][key]) {
      html = html + x + ': ' + info.ffprobe[type][stream][key][x] + '<br>';
    }
    $('#frameModal .modal-body').html(html);
    $('#frameModal').modal('show');
  });
});