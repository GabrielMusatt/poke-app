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

  fetch("https://pokeapi.co/api/v2/pokemon?limit=10000")
    .then((response) => response.json())
    .then((name) => {
      pokemonlist.push(...name.results);
    });

  pokemonlist.map((a) => {
    return a;
  });

  useEffect(() => {
    setFoundResults(foundResults);
  }, [inputValue]);

  const handlesPokes = (event) => {
    // console.log(event.target.value);
    results = [...pokemonlist].filter((pokemon) => {
      if (pokemon.name.startsWith(event.target.value)) {
        const newPokemon = { ...pokemon, test: "a" };
        newPokemon.test = "b";

        fetch(pokemon.url)
          .then((response) => response.json())
          .then((stat) => {
            newPokemon.test = stat;
            console.log(stat, "stat");
            console.log(newPokemon.test, "newPokemon.test");
          });

        // ToDo: why the value from fetch above ("newPokemon.test") is not seen in the consle below?
        console.log(newPokemon, "new");

        return newPokemon;
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
      <StyledResultSection>
        {foundResults &&
          foundResults.map((pokemon, i) => {
            return <p key={i}>{pokemon.name}</p>;
          })}
      </StyledResultSection>
      <StyledOwnedSection>Owned</StyledOwnedSection>
    </StyledPokedex>
  );
};

export default Pokedex;
