import AppForm from '../app-components/Form/AppForm';

Vue.component('workflow-state-form', {
    mixins: [AppForm],
    props: ["worflowid"],
    data: function() {
        return {
            form: {
                name:  '' ,
                workflow_id:  this.worflowid,
                isactive:  false ,

            }
        }
    }

});
