<?php

class PaymentManagementController extends \BaseController {

    public function index(){
        return View::make('payment_management.index')->render();
    }

    public function ajaxLoadPaymentMethodsList() {
        $payment_methods = OrganizationPaymentMethod::orderBy('primary_card', 'desc')->paginate(10);
        return View::make('payment_management._ajax_partials.payment_methods_list', compact('payment_methods'))->render();
    }

    public function ajaxSaveNewPaymentMethod()
    {
        $organization_id = Session::get('user-organization-id');

        $credit_card_type = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('credit_card_type'));
        $credit_card_number = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('credit_card_number'));
        $expiry_month = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('expiry_month'));
        $expiry_year = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('expiry_year'));
        $cvv = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('cvv'));
        $card_owner_name = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('card_owner_name'));
        $card_owner_address = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('card_owner_address'));
        $primary_card = GeneralCommonFunctionHelper::sanitizeUserInput(Input::has('primary_card')? Input::get('primary_card'): 0);

        // make other cards not primary - if this card is marked as primary card
        if($primary_card == 1) {
            DB::table('organization_payment_methods')
                ->where('organization_id', $organization_id)
                ->update(array('primary_card' => 0));
        }

        // create the new payment method
        $data_organization_payment_method = array(
            'organization_id' => $organization_id,
            'credit_card_type' => $credit_card_type,
            'credit_card_number' => $credit_card_number,
            'expiry_month' => $expiry_month,
            'expiry_year' => $expiry_year,
            'cvv' => $cvv,
            'card_owner_name' => $card_owner_name,
            'card_owner_address' => $card_owner_address,
            'primary_card' => $primary_card,
            'status' => 'Pending',
        );
        $organization_payment_method = OrganizationPaymentMethod::create($data_organization_payment_method);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $organization_payment_method->id,
            'data' => $data_organization_payment_method
        );
        AuditTrail::addAuditEntry("OrganizationPaymentMethod", json_encode($audit_action));

        return "Successfully Updated";
    }

    public function ajaxUpdatePaymentMethod()
    {
        $payment_method_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('payment_method_id'));

        $organization_id = GeneralCommonFunctionHelper::sanitizeUserInput(Session::get('user-organization-id'));

        $organization_payment_method = OrganizationPaymentMethod::find($payment_method_id);

        if(is_object($organization_payment_method)) {
            $credit_card_type = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('credit_card_type'));
            $credit_card_number = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('credit_card_number'));
            $expiry_month = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('expiry_month'));
            $expiry_year = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('expiry_year'));
            $cvv = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('cvv'));
            $card_owner_name = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('card_owner_name'));
            $card_owner_address = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('card_owner_address'));
            $primary_card = GeneralCommonFunctionHelper::sanitizeUserInput(Input::has('primary_card')? Input::get('primary_card'): 0);

            // make other cards not primary - if this card is marked as primary card
            if($primary_card == 1) {
                DB::table('organization_payment_methods')
                    ->where('organization_id', $organization_id)
                    ->update(array('primary_card' => 0));
            }

            // update payment method
            $data_organization_payment_method = array(
                'credit_card_type' => $credit_card_type,
                'credit_card_number' => $credit_card_number,
                'expiry_month' => $expiry_month,
                'expiry_year' => $expiry_year,
                'cvv' => $cvv,
                'card_owner_name' => $card_owner_name,
                'card_owner_address' => $card_owner_address,
                'primary_card' => $primary_card,
            );
            $organization_payment_method->update($data_organization_payment_method);

            $audit_action = array(
                'action' => 'update',
                'model-id' => $organization_payment_method->id,
                'data' => $data_organization_payment_method
            );
            AuditTrail::addAuditEntry("OrganizationPaymentMethod", json_encode($audit_action));

            return "Successfully Updated";

        }

        return "Payment Method Not Found.";

    }

    public function ajaxLoadModulesList()
    {
        $type = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('type'));

        $organization_id = Session::get('user-organization-id');

        $build_query = Web360Module::where('status', 1);

        if($type == 'overview') {
            // if overview only load enabled list of organizations
            $enabled_list_of_web360_module_ids = Web360ModuleOrganizationAssignment::where('status', 'Enabled')
                ->where('organization_id', $organization_id)
                ->lists('web360_module_id');

            $build_query->whereIn('id', $enabled_list_of_web360_module_ids);
        }

        $modules = array();
        $web360_modules = $build_query->paginate(10);

        foreach($web360_modules as $web360_module) {
            $modules[] = array(
                'module_id' => $web360_module->id,
                'module_name' => $web360_module->module_name,
                'module_rate' => $web360_module->module_rate,
                'module_rate_base' => $web360_module->module_rate_base,
                'last_web360_module_organization_assignment' => Web360ModuleOrganizationAssignment::where('organization_id', $organization_id)
                    ->where('web360_module_id', $web360_module->id)
                    ->orderBy('id', 'desc')
                    ->first(),
                'assigned_employees_list' => implode(',', Web360ModuleEmployeeAssignment::where('web360_module_id', $web360_module->id)->lists('employee_id')),
            );
        }

        return View::make('payment_management._ajax_partials.modules_list', compact('modules'))->render();
    }

    public function ajaxUpdateModuleOrganizationAssignment()
    {
        $date_time = date('Y-m-d h:i:sa');

        $status = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('module_status'));
        $module_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('module_id'));
        $organization_id = Session::get('user-organization-id');
        $employee_id = Session::get('user-id');

        $assigned_employee_count = Web360ModuleEmployeeAssignment::where('status', 'Enabled')
            ->where('web360_module_id', $module_id)
            ->count();

        if($status == 'Enabled') {
            // create a new record
            $data_web360_module_organization_assignment = array(
                'web360_module_id' => $module_id,
                'organization_id' => $organization_id,
                'enabled_date_time' => $date_time,
                'disabled_date_time' => null,
                'status' => $status,
                'enabled_by' => $employee_id,
                'disabled_by' => null,
                'assigned_employee_count' => $assigned_employee_count
            );

            $web360_module_organization_assignment = Web360ModuleOrganizationAssignment::create($data_web360_module_organization_assignment);
        } else {
            // disable the existing record
            $web360_module_organization_assignment = Web360ModuleOrganizationAssignment::where('status', 'Enabled')
                ->where('organization_id', $organization_id)
                ->where('web360_module_id', $module_id)
                ->first();

            if(is_object($web360_module_organization_assignment)) {
                $data_web360_module_organization_assignment = array(
                    'status' => $status,
                    'disabled_date_time' => $date_time,
                    'disabled_by' => $employee_id,
                );
                $web360_module_organization_assignment->update($data_web360_module_organization_assignment);
            }
        }

        return "Successfully Updated.";

    }

    public function ajaxLoadModuleEmployeesList()
    {
        $organization_id = Session::get('user-organization-id');

        $web360_module_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('web360_module_id'));
        $selected_employees_list = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('selected_employees_list'));

        $selected_employees_list = explode(',', $selected_employees_list);

        $employees = Employee::where('organization_id', $organization_id)->orderBy('given_name')->paginate(10);

        return View::make('payment_management._ajax_partials.selectable_employees_list', compact('web360_module_id', 'selected_employees_list', 'employees'))->render();


    }

    public function ajaxAssignEmployeeToModule()
    {
        $date_time = date('Y-m-d h:i:sa');

        // get the module_id, employee_id and assign status
        $web360_module_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('web360_module_id'));
        $employee_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('employee_id'));
        $assigned = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('assigned'));

        syslog(LOG_INFO, '$web360_module_id -- ' . $web360_module_id);
        syslog(LOG_INFO, '$employee_id -- ' . $employee_id);
        syslog(LOG_INFO, '$assigned -- ' . $assigned);

        // check if this employee already assigned
        $web360_module_employee_assignment = Web360ModuleEmployeeAssignment::where('employee_id', $employee_id)
            ->where('web360_module_id', $web360_module_id)
            ->where('status', 'Enabled')
            ->first();

        if($assigned == 'Enabled') {
            // if assign then create a record in the assignment table
            syslog(LOG_INFO, 'assigned ');
            if(!is_object($web360_module_employee_assignment)) {
                $data_web360_module_employee_assignment = array(
                    'web360_module_id' => $web360_module_id,
                    'employee_id' => $employee_id,
                    'enabled_date_time' => $date_time,
                    'disabled_date_time' => null,
                    'status' => 'Enabled',
                    'enabled_by' => Session::get('user-id'),
                    'disabled_by' => null,
                );

                $web360_module_employee_assignment = Web360ModuleEmployeeAssignment::create($data_web360_module_employee_assignment);
            }
        } else {
            // if unassign then remove the assigned record
            syslog(LOG_INFO, 'un assigned ');
            if(is_object($web360_module_employee_assignment)) {
                syslog(LOG_INFO, 'un assigned 22');
                $web360_module_employee_assignment->update(
                    array(
                        'status' => 'Disabled',
                        'disabled_date_time' => $date_time,
                        'disabled_by' => Session::get('user-id')
                    ));
            }
        }


        // get the new list of assigned_employees_list and send back the comma separated list

        $assigned_employees_list = implode(',', Web360ModuleEmployeeAssignment::where('web360_module_id', $web360_module_id)
            ->where('status', 'Enabled')
            ->lists('employee_id'));

        return $assigned_employees_list;
    }

    public function ajaxLoadPaymentHistoryList()
    {
        $organization_id = Session::get('user-organization-id');

        $invoices = Web360OrganizationInvoiceMasterRecord::where('organization_id', $organization_id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return View::make('payment_management._ajax_partials.invoices_list', compact('invoices'))->render();
    }

    public function generateInvoices()
    {
        $from_date = Input::get("from_date");
        $to_date = Input::get("to_date");
    }



}
