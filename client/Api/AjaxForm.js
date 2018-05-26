import blendExchange from '@/Api/BlendExchangeApi'

export default {
    scaffold () {
        return {
            data: {},
            errors: {},
            status: '',
            files: {},
            meta: {
                uploadProgress: 0
            }
        };
    },
    async submit ({form, meta = {}, endpoint}) {
        form.status = 'pending';
        try {
            var result = await blendExchange.submitForm({
                submissionData: form.data,
                metaData: meta,
                endpoint,
                options: {}
            });
            form.status = 'completed';
            return result;
        } catch (err) {
            form.status = 'failed';
            if (typeof err.response === 'undefined') {
                throw err;
            }
            if (err.response.status !== 422) {
                if (err.response.data.type === 'error') {
                    form.serverError = err.response.data.error;
                } else {
                    form.serverError = 'There was an internal error attempting to complete your request.';
                }

            } else {
                form.serverError = err.response.data.error;
                form.errors = err.response.data.errors;
            }
        }
    }
}