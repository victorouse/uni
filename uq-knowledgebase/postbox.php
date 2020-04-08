<div class="form-group">
    <label for="inputTitle">Title</label>
    <input type="text" class="form-control" name="title" placeholder="Enter title">
</div>
<textarea class="input-block-level summernote" name="comment" rows="18">
</textarea>

<script type="text/javascript">
    $(document).ready(function() {
        $('.summernote').summernote({
            height: "250px"
        });

    });

    var postForm = function(me) {
        var content = $(me).find('textarea[name="comment"]').val($(me).find('.summernote').code());
    }
</script>
