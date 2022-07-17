import React, { useState, useEffect } from "react";
import {
  StyledPokedex,
  StyledResultSection,
  StyledOwnedSection,
} from "./styled";
import PokeSearch from "./Search";

const Pokedex = () => {
  let pokemonlist = [];
  let results = [];
  const [inputValue, setInputValue] = useState("");
  const [foundResults, setFoundResults] = useState(pokemonlist);

  fetch("https://pokeapi.co/api/v2/pokemon?limit=10000").then((response) =>
    response.json().then((name) => pokemonlist.push(...name.results))
  );

  useEffect(() => {
    setFoundResults(foundResults);
  }, [inputValue]);

  const handlesPokes = (event) => {
    console.log(event.target.value);
    results = [...pokemonlist].filter((a) => {
      if (a.name.startsWith(event.target.value)) {
        return a;
      }
    });

    setFoundResults(results);
  };

  return (
    <StyledPokedex>
      <PokeSearch
        handlesPokes={handlesPokes}
        inputValue={inputValue}
        setInputValue={setInputValue}
      />
      <StyledResultSection>Result section</StyledResultSection>
      <StyledOwnedSection>Selected</StyledOwnedSection>
    </StyledPokedex>
  );
};

export default Pokedex;
