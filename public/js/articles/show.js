$(document).ready(function () {

    let Model = {
        ajax: (url, method, csrfToken, formData, successCallback, failureCallback) => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })

            return $.ajax({
                url: url,
                method: method,
                data: formData,
                success: (data) => successCallback(data),
                fail: (data) => failureCallbacK(data)
            })
        }
    }


    let ArticleRatingView = (function () {
        this.btnLike = $('.form-article-like').first()
        this.btnDislike = $('.form-article-dislike').first()

        this.liked = false
        this.disliked = false


        this.initRating = () => {
            if (this.btnLike.hasClass('text-primary')) {
                this.liked = true
            
            } else if (this.btnDislike.hasClass('text-primary')) {
                this.disliked = true
            }
        }

        this.like = (likes) => {
            this.btnLike.addClass('text-primary')
            this.btnLike.removeClass('text-muted')
            this.liked = true
            this.btnLike.find('.article-likes-count').text(likes)
        }

        this.unlike = (likes) => {
            this.btnLike.removeClass('text-primary')
            this.btnLike.addClass('text-muted')
            this.liked = false
            this.btnLike.find('.article-likes-count').text(likes)
        }

        this.toggleLike = (likes) => {
            if (this.liked) {
                this.unlike(likes)
            
            } else {
                this.like(likes)
            }
        }

        this.dislike = (dislikes) => {
            this.btnDislike.addClass('text-primary')
            this.btnDislike.removeClass('text-muted')
            this.disliked = true
            this.btnDislike.find('.article-dislikes-count').text(dislikes)
        }

        this.undislike = (dislikes) => {
            this.btnDislike.removeClass('text-primary')
            this.btnDislike.addClass('text-muted')
            this.disliked = false
            this.btnDislike.find('.article-dislikes-count').text(dislikes)
        }

        this.toggleDislike = (dislikes) => {
            if (this.disliked) {
                this.undislike(dislikes)
            
            } else {
                this.dislike(dislikes)
            }
        }

        this.updateLikes = (likes, liked, dislikes, disliked) => {
            if (liked) {
                this.like(likes)
            } else {
                this.unlike(likes)
            }

            if (disliked) {
                this.dislike(dislikes)
            } else {
                this.undislike(dislikes)
            }
        }
    })


    let CommentView = (function (comment) {
        this.id = $(comment).attr('id')

        console.log(comment)

        this.subcommentArea = $(comment).find('.comment-subcomments').first()
        this.initialSubsCount = 3

        if (this.subcommentArea.children().length > this.initialSubsCount) {
            this.subcommentIds = []
            let subs = this.subcommentArea.children()

            for (let i = this.initialSubsCount; i < subs.length; i++) {
                this.subcommentIds.push(subs[i])
                $(subs[i]).addClass('d-none')
                $(subs[i]).attr('id')
            }
            this.subcommentArea.after('<button class="btn btn-block btn-primary btn-load-more mt-3">Load More</button>')
            this.btnLoadMore = this.subcommentArea.next()
        }

        this.titleContainer = $(comment).find('.comment-title').first()
        this.title = this.titleContainer.text().trim()

        this.bodyContainer = $(comment).find('.comment-body').first()
        this.body = this.bodyContainer.text().trim()

        this.createSubForm = $(comment).find('.form-subcomment-create').first()
        this.editForm = $(comment).find('.link-comment-edit').first()
        this.createForm = $(comment).find('.form-comment-delete').first()

        this.btnExpand = $(comment).find('.btn-expand').first()
        this.btnShowCreateSub = $(comment).find('.btn-show-comment-create').first()
        this.btnLike = $(comment).find('.form-comment-like').first()
        this.btnDislike = $(comment).find('.form-comment-dislike').first()

        this.createSubFormShow = false
        this.editFormShow = false
        this.expandedBody = true
        this.liked = false
        this.disliked = false


        this.getIndex = () => $(comment).attr('id').substring(7)

        this.show = () => $(comment).removeClass('d-none')

        this.hide = () => $(comment).addClass('d-none')

        this.remove = () => $(comment).remove()

        this.showCreateSubForm = () => {
            this.createSubForm.removeClass('d-none')
            this.createSubFormShow = true
        }

        this.hideCreateSubForm = () => {
            this.createSubForm.addClass('d-none')
            this.createSubFormShow = false
        }

        this.toggleCreateSubForm = () => {
            if (this.createSubFormShow) {
                this.hideCreateSubForm()
            
            } else {
                this.showCreateSubForm()
            }
        }

        this.clearCreateSubForm = () => {
            this.createSubForm.find('[name="title"]').val('')
            this.createSubForm.find('[name="body"]').val('')
        }

        this.showEditForm = () => {
            this.titleContainer.html(this.getTitleInput())
            this.bodyContainer.html(this.getBodyInput())
            this.bodyContainer.after(this.getEditForm())
            this.btnExpand.addClass('d-none')
            this.editFormShow = true
        }

        this.hideEditForm = () => {
            this.titleContainer.html(this.title)
            this.bodyContainer.html(this.body)
            
            if (this.bodyContainer.next().hasClass('form-comment-edit')) {
                this.bodyContainer.next().remove()
                this.btnExpand.addClass('d-none')
            }
            this.editFormShow = false
        }

        this.toggleEditForm = () => {
            if (this.editFormShow == true) {
                this.hideEditForm()
            
            } else {
                this.showEditForm()
            }
        }

        this.initExpand = () => {
            this.truncateBody()
            this.truncateBody()
        }

        this.expandBody = () => {
            this.bodyContainer.text(this.body)
            this.expandedBody = true
            this.btnExpand.text('Hide')
            this.btnExpand.addClass('expanded')
        }

        this.truncateBody = () => {
            if (this.body.length > 600) {
                if (this.btnExpand.hasClass('d-none')) {
                    this.btnExpand.removeClass('d-none')
                }
                let shortenText = truncate(this.body, 600)
                
                this.bodyContainer.text(shortenText)
                this.bodyContainer.after()
                this.expandedBody = false
                this.btnExpand.text('Expand')
                this.btnExpand.removeClass('expanded')
            }
        }

        this.toggleExpand = () => {
            if (this.expandedBody == true) {
                this.truncateBody()
            
            } else {
                this.expandBody()
            }
        }

        this.initRating = () => {
            if (this.btnLike.hasClass('text-primary')) {
                this.liked = true
            
            } else if (this.btnDislike.hasClass('text-primary')) {
                this.disliked = true
            }
        }

        this.initRating()

        this.like = (likes) => {
            this.btnLike.removeClass('text-muted')
            this.btnLike.addClass('text-primary')
            this.liked = true
            this.btnLike.find('.comment-likes-count').text(likes)
        }

        this.unlike = (likes) => {
            this.btnLike.removeClass('text-primary')
            this.btnLike.addClass('text-muted')
            this.liked = false
            this.btnLike.find('.comment-likes-count').text(likes)
        }

        this.toggleLike = (likes) => {
            if (this.liked) {
                this.unlike(likes)
            
            } else {
                this.like(likes)
            }
        }

        this.dislike = (dislikes) => {
            this.btnDislike.removeClass('text-muted')
            this.btnDislike.addClass('text-primary')
            this.disliked = true
            this.btnDislike.find('.comment-dislikes-count').text(dislikes)
        }

        this.undislike = (dislikes) => {
            this.btnDislike.removeClass('text-primary')
            this.btnDislike.addClass('text-muted')
            this.disliked = false
            this.btnDislike.find('.comment-dislikes-count').text(dislikes)
        }

        this.toggleDislike = (dislikes) => {
            if (this.disliked) {
                this.undislike(dislikes)
            
            } else {
                this.dislike(dislikes)
            }
        }

        this.updateLikes = (likes, liked, dislikes, disliked) => {
            if (liked) {
                this.like(likes)
            } else {
                this.unlike(likes)
            }

            if (disliked) {
                this.dislike(dislikes)
            } else {
                this.undislike(dislikes)
                this.btnDislike.removeClass('text-primary')
            }
        }

        this.loadSubs = () => {
            for (let i = 0; i < Math.min(this.initialSubsCount, this.subcommentIds.length); i++) {
                let id = this.subcommentIds.shift()
                $(id).removeClass('d-none')
            }

            if (this.subcommentIds.length == 0) {
                this.subcommentArea.next().remove()
            }
        }

        this.addSubcomment = (subcomment) => this.subcommentArea.prepend(subcomment)
        this.lastSubcomment = () => this.subcommentArea.children(':first-child')

        this.getEditRoute = () => parseRoute(editCommentRoute, {id: this.getIndex()})
        this.getUpdateRoute = () => parseRoute(updateCommentRoute, {id: this.getIndex()})
        this.getDeleteRoute = () => parseRoute(deleteCommentRoute, {id: this.getIndex()})

        this.getTitleInputValue = () => $(comment).find('[name="title"]').first().val().trim()

        this.getBodyInputValue = () => $(comment).find('[name="body"]').first().text().trim()

        this.editFormId = () => 'formComment' + this.getIndex() + 'Edit';

        this.getEditForm = () => '<form id="' + this.editFormId() + '" ' + 
            'action="' + this.getUpdateRoute() + '" method="POST" class="form-comment-edit">' + 
            '<input type="hidden" name="_token" value="' + csrfToken + '">' + 
            '<input type="hidden" name="_method" value="PATCH">' + 
            '<input type="submit" class="btn btn-block btn-primary my-3" value="Update"></form>'

        this.getTitleInput = () => '<input type="text" name="title" form="' + this.editFormId() + '" ' + 
            'class="form-control input-sm" value="' + this.title + '" minlength="5" maxlength="255" ' + 
            'data-parsley-length="[5,255]" ' + 
            'data-parsley-pattern="[\\w\\-\\+\\#\\$\\%\\^\\&\\@\\!\\(\\)\\|\\=\\,\\.\\<\\>\\/\\?\\" ]+" ' + 
            'required style="padding: .1rem .5rem !important;">'

        this.getBodyInput = () => '<textarea name="body" form="' + this.editFormId() + '" ' + 
            'class="form-control input-sm" minlength="1" maxlength="2500" data-parsley-maxlength="2500" rows="5" ' + 
            'data-parsley-pattern="[\\w\\-\\+\\#\\$\\%\\^\\&\\@\\!\\(\\)\\|\\=\\,\\.\\<\\>\\/\\?\\" ]+" ' + 
            'style="padding: .25rem .5rem !important;" required>' + this.body + '</textarea>'

        return this
    })


    const domain = 'http://yourfreshnews.herokuapp.com/'
    const editCommentRoute = domain + 'comments/{id}/edit'
    const updateCommentRoute = domain + 'comments/{id}'
    const deleteCommentRoute = domain + 'comments/{id}'

    const parseRoute = (route, data) => {
        let parsed = route
        let keys = Object.keys(data)
    
        for (let i in keys) {
            parsed = parsed.replace('{'+keys[i]+'}', data[keys[i]])
        }

        return parsed
    }

    const csrfToken = $('meta[name="csrf-token"]').attr('content')

    const getCommentId = (item) => $(item).closest('.comment').attr('id')

    const truncate = (text, length) => text.substring(0, length) + '...'

    const hideForms = () => {
        for (let i in commentViews) {
            commentViews[i].hideCreateSubForm()
            commentViews[i].hideEditForm()
        }
    }



    // Events

    $('body').on('submit', '.form-comment-create', function (e) {
        e.preventDefault()

        for (let i in commentViews) {
            commentViews[i].hideEditForm()
            commentViews[i].hideCreateSubForm()
        }

        let url = $(this).attr('action')
        let formData = $(this).serialize()
        let method = 'POST'
        let successCallback = (data) => {
            let id = $(data).attr('id')
            // let commentView = new CommentView(data)

            $('.comments').prepend(data)

            let commentView = new CommentView($('.comments').children(':first-child')[0])

            commentViews[id] = commentView

            $(this).find('[name="title"]').val('')
            $(this).find('[name="body"]').val('')
        }

        Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
        
    })


    $('body').on('click', '.btn-show-comment-create', function (e) {
        e.preventDefault()

        let id = getCommentId(this)

        for (let i in commentViews) {
            commentViews[i].hideEditForm()

            if (i != id) {
                commentViews[i].hideCreateSubForm()
            }
        }

        if (commentViews[id].createSubFormShow == true) {
            commentViews[id].hideCreateSubForm()
        
        } else {
            commentViews[id].showCreateSubForm()
        }
    })


    $('body').on('click', '.link-comment-edit', function (e) {
        e.preventDefault()

        let id = getCommentId(this)

        for (let i in commentViews) {
            commentViews[i].hideCreateSubForm()

            if (i != id) {
                commentViews[i].hideEditForm()
            }
        }

        if (commentViews[id].editFormShow == true) {
            commentViews[id].hideEditForm()
        
        } else {
            commentViews[id].showEditForm()
        }
    })


    $('body').on('submit', '.form-subcomment-create', function (e) {
        e.preventDefault()

        let id = getCommentId(this)
        let url = $(this).attr('action')
        let formData = $(this).serialize()
        let method = 'POST'
        let successCallback = (data) => {
            commentViews[id].addSubcomment(data)
            commentViews[id].hideCreateSubForm()
            commentViews[id].clearCreateSubForm()

            let subcomment = commentViews[id].lastSubcomment()
            let commentView = new CommentView(subcomment[0])

            commentViews[subcomment.attr('id')] = commentView
        }

        Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
    })


    $('body').on('submit', '.form-comment-edit', function (e) {
        e.preventDefault()

        let id = getCommentId(this)
        let url = $(this).attr('action')
        let formData = $(this).serialize()
        let method = 'PATCH'
        let successCallback = (data) => {
            commentViews[id].title = data.title
            commentViews[id].body = data.body
            commentViews[id].hideEditForm()
        }

        Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
    })


    $('body').on('submit', '.form-comment-delete', function (e) {
        e.preventDefault()

        let id = getCommentId(this)

        for (let i in commentViews) {
            commentViews[i].hideCreateSubForm()
            commentViews[i].hideEditForm()
        }

        if (confirm('Are You sure if You want to delete comment ' + commentViews[id].title + '?')) {
            let url = $(this).attr('action')
            let formData = $(this).serialize()
            let method = 'DELETE'
            let successCallback = (data) => {
                commentViews[id].remove()
                delete commentViews[id]
            }

            Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
        }
    })


    $('body').on('submit', '.form-comment-like', function (e) {
        e.preventDefault()

        let id = getCommentId(this)
        let url = $(this).attr('action')
        let formData = $(this).serialize()
        let method = 'POST'
        let successCallback = (data) => commentViews[id].updateLikes(data.likes, data.liked, data.dislikes, data.disliked)

        Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
    })


    $('body').on('submit', '.form-comment-dislike', function (e) {
        e.preventDefault()

        let id = getCommentId(this)
        let url = $(this).attr('action')
        let formData = $(this).serialize()
        let method = 'POST'
        let successCallback = (data) => commentViews[id].updateLikes(data.likes, data.liked, data.dislikes, data.disliked)

        Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
    })


    $('body .form-article-like').on('submit', function (e) {
        e.preventDefault()

        let id = getCommentId(this)
        let url = $(this).attr('action')
        let formData = $(this).serialize()
        let method = 'POST'
        let successCallback = (data) => articleRatingView.updateLikes(data.likes, data.liked, data.dislikes, data.disliked)

        Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
    })


    $('body .form-article-dislike').on('submit', function (e) {
        e.preventDefault()

        let id = getCommentId(this)
        let url = $(this).attr('action')
        let formData = $(this).serialize()
        let method = 'POST'
        let successCallback = (data) => articleRatingView.updateLikes(data.likes, data.liked, data.dislikes, data.disliked)

        Model.ajax(url, method, csrfToken, formData, successCallback, console.log)
    })




    let comments = $('.comment')
    let commentViews = []
    let articleRatingView = new ArticleRatingView()

    articleRatingView.initRating()
    
    for (let i = 0; i < comments.length; i++) {
        let id = getCommentId(comments[i])
        let comment = new CommentView(comments[i])
        let subcomments = $(comments[i]).find('.comment-subcomments').children()

        commentViews[id] = comment
    }


    $('body .btn-load-more').on('click', function (e) {
        let id = getCommentId(this)

        commentViews[id].loadSubs()
    })
    

    $('body').on('submit', '.form-article-delete', function (e) {
        e.preventDefault()

        let articleTitle = $('.article-title').first().text()

        if (confirm('Are You sure You want to delete article "' + articleTitle + '"?')) {
            $(this).unbind('submit').submit()
        }
    })


    // $('.form-delete-post').submit(function (e) {
    //     e.preventDefault()

    //     if (confirm('Are You sure you want remove article: "{{ $article->title }}"?')) {
    //         $(this).unbind('submit').submit()
    //     }
    // })
})