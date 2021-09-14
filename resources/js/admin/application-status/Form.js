import AppForm from '../app-components/Form/AppForm';

Vue.component('application-status-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                task_id:  '' ,
                status_id:  '' ,
                user:  '' ,
                description:  '' ,
                
            }
        }
    }

});