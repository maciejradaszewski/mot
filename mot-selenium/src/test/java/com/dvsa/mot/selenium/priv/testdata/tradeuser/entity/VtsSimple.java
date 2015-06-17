package com.dvsa.mot.selenium.priv.testdata.tradeuser.entity;


import java.util.List;

public class VtsSimple {

    private String name;
    private String addressLine1;
    private List<Integer> classes;

    public VtsSimple(String name, String addressLine1, List<Integer> classes) {
        this.name = name;
        this.addressLine1 = addressLine1;
        this.classes = classes;
    }

    public String getName() {
        return name;
    }

    public String getAddressLine1() {
        return addressLine1;
    }

    public List<Integer> getClasses() {
        return classes;
    }
}
