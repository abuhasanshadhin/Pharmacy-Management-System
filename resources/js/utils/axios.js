export function handleErrors(error, callback) {
    if (error.response) {
        const {
            response: {
                data: { message = '', errors = {} } = {},
                status,
                statusText
            },
        } = error;

        if (status == 422 && Object.keys(errors).length > 0) {
            Object.values(errors)
                .flat(Infinity)
                .forEach(function (message) {
                    callback(message)
                });
        } else if (status == 401) {
            window.location.reload();
        } else if (message) {
            callback(message);
        } else {
            callback(statusText)
        }
    } else {
        callback(error.message);
    }

    return Promise.reject(error);
}
