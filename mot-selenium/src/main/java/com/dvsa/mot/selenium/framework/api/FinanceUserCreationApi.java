package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class FinanceUserCreationApi extends BaseApi {

    private static final String FINANCE_USER_PATH = "testsupport/financeuser";
    private static final String SUPER_FINANCE_USER_PATH = "testsupport/vm9913user";
    public FinanceUserCreationApi() {
        super(testSupportUrl());
    }

    public Person createFinanceUser(String diff) {
        return createUser(diff, FINANCE_USER_PATH);
    }

    public Person createFinanceUser() {
        return createUser(null, FINANCE_USER_PATH);
    }

    public Person createSuperFinanceUser() {
        return createUser(null, SUPER_FINANCE_USER_PATH);
    }

    private Person createUser(String diff, String path){
        JsonObjectBuilder financeUserData = Json.createObjectBuilder();

        if (null != diff) {
            financeUserData.add("diff", diff);
        }

        JsonObject response = post(path, financeUserData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Person(Integer.toString(responseData.getInt("personId")), null,
                responseData.getString("firstName"), "", responseData.getString("surname"), 0, 0,
                null, null, "test@email.com", null, null, Address.ADDRESS_ADDRESS1, null,
                new Login(responseData.getString("username"), responseData.getString("password")),
                null, null);
    }

}
