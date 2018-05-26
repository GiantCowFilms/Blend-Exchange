import axios from 'axios'
import ls from 'local-storage'

if (ls.get('auth-token') !== null) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${ls.get('auth-token')}`;
}

export default {
    transformApiResponse(format) {
        // if (data.type === 'collection') {
        //     output = data.items;
        // }
        // if (data.type === 'item') {
        //     output = data.item;
        // }
        var data = format.data;
        for (var item in data) {
            if (
                typeof data[item] === 'object' &&
                data[item] !== null &&
                typeof data[item].data !== 'undefined'
            ) {
                data[item] = data[item].data
            }
        }

        var output = {
            data,
            meta: format.meta || {}
        };

        if (format.type === 'requires_modification') {
            output.meta.type = format.type;
            output.meta.modification_token = format.token;
            output.meta.endpoint = format.endpoint;
        }
        return output;
    },
    transformApiRequest(submissionFields, metaData) {
        return {
            data: submissionFields,
            meta: metaData
        };
    },
    async getEndpoint(endpoint, options = {}) {
        var response = await axios.get('/api' + endpoint, options);
        return this.transformApiResponse(response.data);
    },
    async setEndpoint(endpoint, data, options = {}) {
        var response = await axios.post('/api' + endpoint, data, options);
        return this.transformApiResponse(response.data);
    },
    async setFiles(action, files, options) {
        for (let [key, file] of Object.entries(files)) {
            await axios.post(action, file, Object.assign(
                options,
                {
                    onUploadProgress: pe => {
                        options.fileUploadProgress(key,pe)
                    }
                }
            ));
        }
    },
    async getResource ({endpoint, meta, options = {}}) {
        let headers = {
            'X-Resource-Token': `${meta.token}`
        };
        if(typeof options.headers !== 'undefined') {
            Object.assign(options.headers,headers);
        } else {
            options.headers = headers;
        }
        return await this.getEndpoint(endpoint,options);
    },
    async submitForm({ submissionData, submissionFiles = {}, metaData, endpoint, options }) {
        var submissionFields = {};
        var submissionUploads = submissionFiles;

        for (let field of Object.keys(submissionData)) {
            if (!(submissionData[field] instanceof File)) {
                submissionFields[field] = submissionData[field]
            }
            if (submissionData[field] instanceof File) {
                submissionUploads[field] = submissionData[field]
            }
        }

        var apiRequest = this.transformApiRequest(submissionFields, metaData);

        var response = await this.setEndpoint(endpoint, apiRequest, options);

        if (
            typeof response !== 'undefined' &&
            typeof response.meta !== 'undefined' &&
            response.meta.type === 'requires_modification'
        ) {
            await this.setFiles(response.meta.endpoint, submissionUploads, {
                headers: {
                    'X-Resource-Token': `${response.meta.modification_token}`,
                    'Content-Type': 'application/octet-stream'
                },
                fileUploadProgress: options.fileUploadProgress
            });
        }
        return response.data;
    }
}