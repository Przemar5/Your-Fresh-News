let InputValidator = (function(element) {
    this.inputs = []
    this.prefix = 'validator'
    
    const props = ['required', 'between', 'minlength', 'maxlength', 'pattern']

    const capitalize = (word) => word.charAt(0).toUpperCase() + word.slice(1)

    const extractProperty = (messagePostfix) => (props, funcs = []) => {
        let result = []

        for (let i in props) { 
            let fullPropName = this.prefix + capitalize(props[i])
            
            if (messagePostfix != null) {
                fullPropName += capitalize(messagePostfix)
            }

            if (element.dataset.hasOwnProperty(fullPropName)) {
                if (funcs[i] == null) {
                    result[props[i]] = this.element.dataset[fullPropName]

                } else {
                    result[props[i]] = funcs[i](this.element.dataset[fullPropName])
                }
            }
        }

        return result;
    }

    const extractValidators = () => {
        let funcs = [
            (value) => value,
            (value) => {
                let min = parseInt(value.substring(value.indexOf('[') + 1, value.indexOf(',')).trim())
                let max = parseInt(value.substring(value.indexOf(',') + 1, value.indexOf(']')).trim())

                return [min, max]
            },
            (value) => parseInt(value),
            (value) => parseInt(value),
            (value) => value
        ]
        return extractProperty()(props, funcs)
    }

    const extractMessages = () => {
        return extractProperty('message')(props)
    }

    const extractGenericMessage = () => {
        value = this.element.dataset[this.prefix + 'ErrorMessage']

        if (value != undefined && value != null) {
            this.genericMessage = value
        }
    }

    this.required = () => this.element.value.trim() != ''
    this.minlength = () => this.element.value.trim().length >= this.validators.minlength
    this.maxlength = () => this.element.value.trim().length <= this.validators.maxlength
    this.between = () => this.element.value.trim().length >= this.validators.between[0] && 
                        this.element.value.trim().length <= this.validators.between[1]
    this.pattern = () => (new RegExp(this.validators.pattern)).test(this.element.value.trim())

    this.validate = () => {
        this.validates = true
        this.error = false

        for (let i in props) {
            this.validates = this[props[i]]()
            
            if (!this.validates) {
                if (this.genericMessage != undefined && this.genericMessage != null) {
                    this.error = this.genericMessage
                
                } else {
                    this.error = this.messages[props[i]]
                }
                
                break
            }
        }

        return this.validates
    }

    this.element = element
    this.validators = extractValidators()
    this.messages = extractMessages()
    this.genericMessage = extractGenericMessage()
    this.error = false
    this.validates = true
})


let ValidatorView = {

    showError: (element, message) => {
        let error = '<small class="text-danger d-block error" style="position:relative; bottom:.5rem;">' + message + '</small>'

        if ($(element).next().hasClass('error')) {
            $(element).next().remove()
        }

        $(element).addClass('input-error')
        $(element).after(error)
    },

    removeError: (element) => {
        if ($(element).next().hasClass('error')) {
            $(element).next().remove()
        }

        $(element).removeClass('input-error')
    },

    showErrors: (elements, messages) => {
        for (let i in elements) {
            if (messages[i] != null) {
                this.showError(elements[i], messages[i])
            }
        }
    },

    removeErrors: (elements) => {
        for (let i in elements) {
            this.removeErrors(elements[i])
        }
    }
}



let FormValidator = (function (form) {
    this.form = form
    this.inputValidators = []
    this.inputs = $(this.form).find('[data-validator]')
    this.inputCount = this.inputs.length
    this.errors
    this.validates

    for (let i = 0; i < this.inputCount; i++) {
        this.inputValidators.push(new InputValidator(this.inputs[i]))
    }

    this.validate = () => {
        this.validates = true
        this.errors = []

        for (let i in this.inputValidators) {
            if (!this.inputValidators[i].validate()) {
                this.validates = false
                this.errors.push(this.inputValidators[i].error)
            }
        }

        return this.validates
    }

    this.filterInvalid = () => this.inputValidators.filter((validator) => validator.error != null)

    this.filterValid = () => this.inputValidators.filter((validator) => validator.error == null)
})