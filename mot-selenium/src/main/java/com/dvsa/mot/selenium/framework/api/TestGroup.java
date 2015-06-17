package com.dvsa.mot.selenium.framework.api;

public enum TestGroup {
    group1(1, "12"), group2(2, "3+");

    public final static TestGroup ALL = null;

    public final int group;
    public final String description;

    private TestGroup(int group, String description) {
        this.group = group;
        this.description = description;
    }

}