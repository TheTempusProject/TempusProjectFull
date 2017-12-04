<script language="JavaScript" type="text/javascript">tinymce.init({ selector:'#blogPost' });</script>
<div class="row">
    <div class="col-sm-8 blog-main">
        <div class="blog-post">
            <h2 class="blog-post-title">{title}</h2>
            <p class="blog-post-meta">{DTC}{created}{/DTC} by <a href="{BASE}admin/user/view/{author}">{authorName}</a></p>
            {content}
        </div><!-- /.blog-post -->
    </div><!-- /.blog-main -->
</div><!-- /.row -->
<form action="" method="post" class="form-horizontal"  enctype="multipart/form-data">
    <legend>New Blog Post</legend>
    <div class="form-group">
        <label for="title" class="col-lg-3 control-label">Title</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="title" id="title" value="{title}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-6 col-lg-offset-3 btn-group">
            <button type="button" class="btn btn-sm btn-primary" onclick="insertTag ('blogPost', 'c');">&#10004;</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="insertTag ('blogPost', 'x');">&#10006;</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="insertTag ('blogPost', '!');">&#10069;</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="insertTag ('blogPost', '?');">&#10068;</button>
        </div>
    </div>
    <div class="form-group">
        <label for="blogPost" class="col-lg-3 control-label">Post</label>
        <div class="col-lg-6">
            <textarea class="form-control" name="blogPost" maxlength="2000" rows="10" cols="50" id="blogPost">{content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-6 col-lg-offset-3">
            <button name="submit" value="publish" type="submit" class="btn btn-lg btn-primary">Publish</button>
            <button name="submit" value="saveasdraft" type="submit" class="btn btn-lg btn-primary">Save as Draft</button>
            <button name="submit" value="preview" type="submit" class="btn btn-lg btn-primary">Preview</button>
        </div>
    </div>
    <input type="hidden" name="token" value="{TOKEN}">
</form>