package uk.gov.dvsa.domain.model;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
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

    @Override
    public String toString() {
        return "AeDetails{" +
                "id=" + id +
                ", aeRef='" + aeRef + '\'' +
                ", aeName='" + aeName + '\'' +
                '}';
    }
}
