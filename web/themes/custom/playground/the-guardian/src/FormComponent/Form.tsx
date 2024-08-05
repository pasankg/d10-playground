import { useContext, useEffect, useState } from "react";
import "./Form.css";
import { searchContext } from "../App";

function Form() {
  const guardian = useContext(searchContext);
  const [term, setTerm] = useState("");
  const [results, setResult] = useState([]);

  function handleSearchTerm(element: object) {
    if (element.target.value.length > 5) {
      setTerm(element.target.value);
    }
  }

  useEffect(() => {
    console.log(term);
    if (term.length > 5) {
      const response = guardian.content
        .search(`${term}`)
        .then((response) => {
          console.log(response);
          if (response.results.length > 0) {
            setResult(response.results);
            console.log(results);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    }
  }, [term]);

  return (
    <div className="search-wrapper">
      <div className="search-elements">
        <h1>Search</h1>
        <input
          type="text"
          placeholder="What are you after.."
          onChange={handleSearchTerm}
        />
        <label className="search-term" htmlFor="search-term">
          Results for <span className="term">{`${term}`}</span>
        </label>
        <div className="results">
          {results.map((item, index) => (
            <li key={index}> {item.webTitle} </li>
          ))}
        </div>
      </div>
    </div>
  );
}
export default Form;
