package com.dvsa.mot.selenium.priv.testdata.tradeuser.entity;


import java.util.List;

public class AeSimple {

    private String name;
    private int slotsCount;
    private List<VtsSimple> vtses;

    public AeSimple(String name, int slotsCount, List<VtsSimple> vtses) {
        this.name = name;
        this.slotsCount = slotsCount;
        this.vtses = vtses;
    }

    public String getName() {
        return name;
    }

    public int getSlotsCount() {
        return slotsCount;
    }

    public List<VtsSimple> getVtses() {
        return vtses;
    }
}
