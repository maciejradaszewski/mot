package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class FinanceUserCreationApi extends BaseApi {

    public FinanceUserCreationApi() {
        super(testSupportUrl());
    }

    public Person createFinanceUser(String diff) {

        JsonObjectBuilder financeUserData = Json.createObjectBuilder();

        if (null != diff) {
            financeUserData.add("diff", diff);
        }

        JsonObject response = post("testsupport/financeuser", financeUserData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Person(Integer.toString(responseData.getInt("personId")), null,
                responseData.getString("firstName"), "", responseData.getString("surname"), 0, 0,
                null, null, "test@email.com", null, null, Address.ADDRESS_ADDRESS1, null,
                new Login(responseData.getString("username"), responseData.getString("password")),
                null, null);
    }

    public Person createFinanceUser() {
        return createFinanceUser(null);
    }

}
