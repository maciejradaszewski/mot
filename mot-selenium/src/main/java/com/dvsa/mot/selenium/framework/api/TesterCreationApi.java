package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;

import javax.json.Json;
import javax.json.JsonArrayBuilder;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.Collection;
import java.util.Collections;

import static com.dvsa.mot.selenium.datasource.Site.POPULAR_GARAGES;

public class TesterCreationApi extends BaseApi {

    public static enum TesterStatus {

        QLFD("Qualified"),
        ITRN("Initial training needed"),
        SPND("Suspended");

        public final String description;

        private TesterStatus(String description) {
            this.description = description;
        }

    }

    public TesterCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createTester(Collection<Integer> vtsIds, TestGroup testGroup, TesterStatus status,
            Login schm, String diff, Boolean accountClaimRequired, Boolean passwordChangeRequired) {

        Person person = createTesterAsPerson(vtsIds, testGroup, status, schm, diff,
            accountClaimRequired, passwordChangeRequired);
        return new Login(person.login.username, person.login.password);
    }

    public Person createTesterAsPerson(Collection<Integer> vtsIds, TestGroup testGroup,
            TesterStatus status, Login schm, String diff,
        Boolean accountClaimRequired, Boolean passwordChangeRequired) {
        JsonObjectBuilder testerCreationData = Json.createObjectBuilder();

        JsonArrayBuilder vtsIdsArrayBuilder = Json.createArrayBuilder();

        for (Integer vtsId : vtsIds) {
            vtsIdsArrayBuilder.add(vtsId);
        }
        testerCreationData.add("siteIds", vtsIdsArrayBuilder);

        RequestorAttachment.attach(schm, testerCreationData);

        if (null != diff) {
            testerCreationData.add("diff", diff);
        }

        if (null != testGroup) {
            testerCreationData.add("testGroup", String.valueOf(testGroup.group));
        }

        if (null != status) {
            testerCreationData.add("status", status.toString());
        }

        testerCreationData.add("accountClaimRequired", accountClaimRequired);
        testerCreationData.add("passwordChangeRequired", passwordChangeRequired);

        JsonObject response = post("testsupport/tester", testerCreationData.build());

        JsonObject responseData = response.getJsonObject("data");

        return new Person(Integer.toString(responseData.getInt("personId")), "Mr",
                responseData.getString("firstName"), responseData.getString("middleName"),
                responseData.getString("surname"), 0, 0,
                null, null, "test@email.com", null, null, Address.ADDRESS_ADDRESS1, null,
                new Login(responseData.getString("username"), responseData.getString("password")),
                null, null);
    }

    public Login createTester(TesterData data, Login schm) {

        JsonObjectBuilder testerCreationData = Json.createObjectBuilder();

        JsonArrayBuilder vtsIdsArrayBuilder = Json.createArrayBuilder();
        for (Integer vtsId : data.vtsIds) {
            vtsIdsArrayBuilder.add(vtsId);
        }
        testerCreationData.add("siteIds", vtsIdsArrayBuilder);
        if (null != data.testGroup) {
            testerCreationData.add("testGroup", String.valueOf(data.testGroup.group));
        }
        if (null != data.status) {
            testerCreationData.add("status", data.status.toString());
        }
        if (null != data.username) {
            testerCreationData.add("username", data.username);
        }
        if (null != data.firstName) {
            testerCreationData.add("firstName", data.firstName);
        }
        if (null != data.surname) {
            testerCreationData.add("surname", data.surname);
        }
        if (null != data.emailAddress) {
            testerCreationData.add("emailAddress", data.emailAddress);
        }
        if (null != data.addressLine1) {
            testerCreationData.add("addressLine1", data.addressLine1);
        }
        if (null != data.phoneNumber) {
            testerCreationData.add("phoneNumber", data.phoneNumber);
        }

        testerCreationData.add("accountClaimRequired", data.accountClaimRequired);

        RequestorAttachment.attach(schm, testerCreationData);

        JsonObject responseData =
                post("testsupport/tester", testerCreationData.build()).getJsonObject("data");

        return new Login(responseData.getString("username"), responseData.getString("password"));
    }

    public Login createTester(Integer vtsId) {
        return createTester(Collections.singleton(vtsId));
    }

    public Login createTester(Collection<Integer> vtsIds) {
        return createTester(vtsIds, null, null, Login.LOGIN_SCHEME_MANAGEMENT, null, false, false);
    }

    public Person createTesterAsPerson(Collection<Integer> vtsIds) {
        return createTesterAsPerson(vtsIds, null, null, Login.LOGIN_SCHEME_MANAGEMENT,
            null, false, false);
    }

    public Person createTesterAsPerson(Collection<Integer> vtsIds, boolean claimAccountRequired) {
        return createTesterAsPerson(vtsIds, null, null, Login.LOGIN_SCHEME_MANAGEMENT,
            null, claimAccountRequired, false);
    }

    public Login createTester(Collection<Integer> vtsIds, Boolean claimAccountRequired) {
        return createTester(vtsIds, null, null, Login.LOGIN_SCHEME_MANAGEMENT, null,
                claimAccountRequired, false);
    }

    public Login createTester(Collection<Integer> vtsIds, TestGroup testGroup,
            Boolean claimAccountRequired) {
        return createTester(vtsIds, testGroup, null, Login.LOGIN_SCHEME_MANAGEMENT, null,
                claimAccountRequired, false);
    }

    public Login createTester(Collection<Integer> vtsIds, TestGroup testGroup,
        Boolean claimAccountRequired, Boolean passwordChangeRequired) {
        return createTester(vtsIds, testGroup, null, Login.LOGIN_SCHEME_MANAGEMENT, null,
            claimAccountRequired, passwordChangeRequired);
    }

    /**
     * @deprecated for tests create new vts to avoid unexpected site history changes during concurrent test runs
     */
    public Login createTester() {
        return createTester(Collections.singleton(POPULAR_GARAGES.getId()), null, null,
                Login.LOGIN_SCHEME_MANAGEMENT, null, false, false);
    }

    public void createTesterRoleForExistingPerson(Collection<Integer> vtsIds, TestGroup testGroup,
            TesterStatus status, Login person, int personId) {

        JsonObjectBuilder testerCreationData = Json.createObjectBuilder();

        JsonArrayBuilder vtsIdsArrayBuilder = Json.createArrayBuilder();


        for (Integer vtsId : vtsIds) {
            vtsIdsArrayBuilder.add(vtsId);
        }
        testerCreationData.add("siteIds", vtsIdsArrayBuilder);

        if (null != testGroup) {
            testerCreationData.add("testGroup", String.valueOf(testGroup.group));
        }

        if (null != status) {
            testerCreationData.add("status", status.toString());
        }

        RequestorAttachment.attach(person, testerCreationData);
        testerCreationData.add("username", person.username);
        testerCreationData.add("password", person.password);
        testerCreationData.add("personId", personId);

        post("testsupport/tester", testerCreationData.build());
    }

    /**
     * Collection<Integer> vtsIds, TestGroup testGroup, TesterStatus status,
     * Login schm, String firstName, String lastName, String login
     */
    public static class TesterData {
        Collection<Integer> vtsIds;
        TestGroup testGroup;
        TesterStatus status;
        String username, firstName, surname, addressLine1, phoneNumber, emailAddress;
        Boolean accountClaimRequired;

        public TesterData vtsIds(Collection<Integer> vtsIds) {
            this.vtsIds = vtsIds;
            return this;
        }

        public TesterData testGroup(TestGroup testGroup) {
            this.testGroup = testGroup;
            return this;
        }

        public TesterData emailAddress(String emailAddress) {
            this.emailAddress = emailAddress;
            return this;
        }

        public TesterData status(TesterStatus status) {
            this.status = status;
            return this;
        }

        public TesterData firstName(String firstName) {
            this.firstName = firstName;
            return this;
        }

        public TesterData surname(String surname) {
            this.surname = surname;
            return this;
        }

        public TesterData username(String username) {
            this.username = username;
            return this;
        }

        public TesterData addressLine1(String addressLine1) {
            this.addressLine1 = addressLine1;
            return this;
        }

        public TesterData phoneNumber(String phoneNumber) {
            this.phoneNumber = phoneNumber;
            return this;
        }

        public TesterData accountClaimRequired(Boolean required) {
            this.accountClaimRequired = required;
            return this;
        }
    }
}
