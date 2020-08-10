let Comment = (function () {
    Model.call(this)

    this.id
    this.title
    this.body
    this.article_id
    this.user_id

    this.create = (url, csrfToken, formData, successCallback, failureCallback) => {
        if (formData == null) {
            formData = '_method=POST&token=' + csrfToken + '&title=' + this.title + 
                '&body=' + this.body + '&article=' + this.article_id
        }
        return this.ajax(url, 'POST', csrfToken, formData, successCallback, failureCallback)
    }
    
    this.update = (url, csrfToken, formData, successCallback, failureCallback) => {
        if (formData == null) {
            formData = '_method=POST&token=' + csrfToken + '&title=' + this.title + 
                '&body=' + this.body
        }
        return this.ajax(url, 'PATCH', csrfToken, formData, successCallback, failureCallback)
    }

    this.delete = (url, csrfToken, formData, successCallback, failureCallback) => {
        if (formData == null) {
            formData = '_method=DELETE&token=' + csrfToken
        }
        return this.ajax(url, 'DELETE', csrfToken, formData, successCallback, failureCallback)
    }
})