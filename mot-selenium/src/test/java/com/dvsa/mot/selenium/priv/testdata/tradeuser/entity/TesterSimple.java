package com.dvsa.mot.selenium.priv.testdata.tradeuser.entity;


import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.TesterCreationApi;
import org.apache.commons.lang3.RandomStringUtils;

import java.util.Set;

public class TesterSimple {

    private String username, firstName, surname, addressLine1, phoneNumber;
    private Set<VtsSimple> vtses;
    private TestGroup testGroup;
    private TesterCreationApi.TesterStatus status;
    private Boolean accountClaimRequired;

    public TesterSimple(String username, String firstName, String surname, String addressLine1,
            Set<VtsSimple> vtses, TestGroup testGroup, TesterCreationApi.TesterStatus status,
            Boolean accountClaimRequired) {
        this.username = username;
        this.firstName = firstName;
        this.surname = surname;
        this.addressLine1 = addressLine1;
        this.vtses = vtses;
        this.testGroup = testGroup;
        this.status = status;
        this.phoneNumber = RandomStringUtils.randomNumeric(10);
        this.accountClaimRequired = accountClaimRequired;
    }

    public String getAddressLine1() {
        return addressLine1;
    }

    public String getUsername() {
        return username;
    }

    public String getFirstName() {
        return firstName;
    }

    public String getSurname() {
        return surname;
    }

    public Set<VtsSimple> getVtses() {
        return vtses;
    }

    public String getPhoneNumber() {
        return phoneNumber;
    }

    public TestGroup getTestGroup() {
        return testGroup;
    }

    public TesterCreationApi.TesterStatus getStatus() {
        return status;
    }

    public Boolean getAccountClaimRequired() {
        return accountClaimRequired;
    }
}
