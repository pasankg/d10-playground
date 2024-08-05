import Guardian from "guardian-js";
import Form from "./FormComponent/Form";
import { createContext } from "react";

export const searchContext = createContext();

function App() {
  const apiKey: string = import.meta.env.VITE_GUARDIAN_API_KEY || "";
  const guardian = new Guardian(apiKey, false);

  return (
    <>
      <searchContext.Provider value={guardian}>
        <Form />
      </searchContext.Provider>
    </>
  );
}

export default App;
