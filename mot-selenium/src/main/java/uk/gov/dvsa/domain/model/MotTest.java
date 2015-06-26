package uk.gov.dvsa.domain.model;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class MotTest {

    public String motTestNumber;

    @Override
    public String toString() {
        return "MotTest{" +
                "motTestNumber=" + motTestNumber +
                '}';
    }

    public String getMotTestNumber() {
        return motTestNumber;
    }
}
