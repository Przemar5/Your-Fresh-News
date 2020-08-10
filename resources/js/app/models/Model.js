let Model = (function () {
    this.ajax = function (url, method, csrfToken, formData, successCallback, failureCallback) {
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
})