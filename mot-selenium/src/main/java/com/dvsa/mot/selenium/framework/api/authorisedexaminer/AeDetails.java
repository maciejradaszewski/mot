package com.dvsa.mot.selenium.framework.api.authorisedexaminer;

public class AeDetails {

    private int id;
    private String aeRef;
    private String aeName;

    public int getId() {
        return id;
    }

    public String getAeRef() {
        return aeRef;
    }
    
    public String getAeName() {
        return aeName;
    }

    protected void setId(int id) {
        this.id = id;
    }

    protected void setAeRef(String aeRef) {
        this.aeRef = aeRef;
    }
    
    protected void setAeName(String aeName) {
        this.aeName = aeName;
    }

    @Override
    public String toString() {
        return "AeDetails{" +
                "id=" + id +
                ", aeRef='" + aeRef + '\'' +
                ", aeName='" + aeName + '\'' +
                '}';
    }
}
